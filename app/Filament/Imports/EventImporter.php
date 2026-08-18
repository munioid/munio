<?php

namespace App\Filament\Imports;

use App\Models\Event\Event;
use App\Models\Event\Category;
use App\Models\Organization\Organization;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EventImporter extends Importer
{
    protected static ?string $model = Event::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->label('Title')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),

            ImportColumn::make('slug')
                ->label('Slug')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),

            ImportColumn::make('content')
                ->label('Content')
                ->ignoreBlankState()
                ->rules(['nullable', 'string']),

            ImportColumn::make('excerpt')
                ->label('Excerpt')
                ->ignoreBlankState()
                ->rules(['nullable', 'string', 'max:500']),

            ImportColumn::make('start_at')
                ->label('Start At')
                ->requiredMapping()
                ->rules(['required', 'date_format:Y-m-d']),

            ImportColumn::make('end_at')
                ->label('End At')
                ->requiredMapping()
                ->rules(['required', 'date_format:Y-m-d']),

            ImportColumn::make('category_slug')
                ->label('Category Slug')
                ->ignoreBlankState()
                ->rules(['nullable', 'string'])
                ->guess(['category', 'category_slug'])
                ->relationship('category', resolveUsing: function (string $state, EventImporter $importer): ?Category {
                    return $importer->findOrCreateCategory($state);
                }),

            ImportColumn::make('published')
                ->label('Published')
                ->ignoreBlankState()
                ->castStateUsing(fn (mixed $state): mixed => filled($state) ? strtolower(trim((string) $state)) === 'yes' || $state === 'true' || $state === '1' : $state)
                ->rules(['nullable', 'boolean']),

            ImportColumn::make('published_at')
                ->label('Published At')
                ->ignoreBlankState()
                ->rules(['nullable', 'date_format:Y-m-d H:i:s']),

            ImportColumn::make('pricing_type')
                ->label('Pricing Type')
                ->ignoreBlankState()
                ->rules(['nullable', 'string'])
                ->guess(['pricing_type', 'type']),

            ImportColumn::make('price')
                ->label('Price')
                ->ignoreBlankState()
                ->castStateUsing(fn (mixed $state): mixed => filled($state) ? (float) $state : null)
                ->rules(['nullable', 'numeric', 'min:0']),

            ImportColumn::make('stocks')
                ->label('Stocks')
                ->ignoreBlankState()
                ->castStateUsing(fn (mixed $state): mixed => filled($state) ? (int) $state : null)
                ->rules(['nullable', 'integer', 'min:0']),

            ImportColumn::make('external_link')
                ->label('External Link')
                ->ignoreBlankState()
                ->rules(['nullable', 'url', 'max:255']),
        ];
    }

    public static function getExamples(): array
    {
        return [
            // Example 1: Single pricing type — price filled
            [
                'title' => 'Summer Festival 2024',
                'slug' => 'summer-festival-2024',
                'content' => 'Join us for the biggest summer festival of the year with live music, food, and entertainment.',
                'excerpt' => 'The ultimate summer celebration',
                'start_at' => '2024-07-01',
                'end_at' => '2024-07-07',
                'category_slug' => 'festival',
                'published' => 'yes',
                'published_at' => '2024-06-01 10:00:00',
                'pricing_type' => 'single',
                'price' => '50000',
                'stocks' => '100',
                'external_link' => '',
            ],
            // Example 2: Package pricing type — price and stocks empty
            [
                'title' => 'Winter Expo 2024',
                'slug' => 'winter-expo-2024',
                'content' => 'Experience the latest technology and innovations at our winter expo with multiple package tiers available.',
                'excerpt' => 'Winter technology showcase',
                'start_at' => '2024-12-01',
                'end_at' => '2024-12-10',
                'category_slug' => 'expo',
                'published' => 'yes',
                'published_at' => '2024-11-15 14:30:00',
                'pricing_type' => 'package',
                'price' => '',
                'stocks' => '',
                'external_link' => '',
            ],
            // Example 3: URL pricing type — external link filled
            [
                'title' => 'Concert Series 2024',
                'slug' => 'concert-series-2024',
                'content' => 'Enjoy world-class performances from international and local artists. Tickets managed on our partner ticketing platform.',
                'excerpt' => 'International concert series',
                'start_at' => '2024-09-15',
                'end_at' => '2024-09-30',
                'category_slug' => 'concert',
                'published' => 'yes',
                'published_at' => '2024-08-20 09:00:00',
                'pricing_type' => 'url',
                'price' => '',
                'stocks' => '',
                'external_link' => 'https://ticketmaster.com/concert-2024',
            ],
        ];
    }

    public function resolveRecord(): ?Model
    {
        $slug = $this->normalizeSlug($this->data['slug'] ?? null);

        if (blank($slug)) {
            return new Event;
        }

        $record = Event::query()
            ->where('organization_id', $this->getOrganizationId())
            ->where('slug', $slug)
            ->first();

        return $record ?: new Event;
    }

    protected function beforeValidate(): void
    {
        $this->ensureTenant();
    }

    protected function beforeSave(): void
    {
        $this->record->organization_id = $this->getOrganizationId();
        $this->ensureTenant();

        // Store `content` as plain text; convert newlines to HTML breaks
        $content = $this->data['content'] ?? null;
        if (filled($content)) {
            $this->record->content = $this->convertPlainTextToHtml($content);
        }
    }

    /**
     * Convert plain text (with newlines) to minimal HTML for the rich-text field.
     */
    protected function convertPlainTextToHtml(string $text): string
    {
        $escaped = e($text);

        return '<p>'.nl2br($escaped).'</p>';
    }

    public function findOrCreateCategory(mixed $state): ?Category
    {
        $slug = $this->normalizeSlug($state);

        if (blank($slug)) {
            return null;
        }

        $organizationId = $this->getOrganizationId();
        $this->ensureTenant();

        $category = Category::withoutGlobalScopes()
            ->where('organization_id', $organizationId)
            ->where('slug', $slug)
            ->first();

        if ($category) {
            return $category;
        }

        $category = new Category;
        $category->forceFill([
            'organization_id' => $organizationId,
            'name' => Str::headline($slug),
            'slug' => $slug,
            'description' => null,
        ]);
        $category->save();

        return $category;
    }

    protected function ensureTenant(): void
    {
        if (Filament::getTenant() instanceof Organization) {
            return;
        }

        Filament::setTenant(Organization::query()->findOrFail($this->getOrganizationId()), true);
    }

    protected function normalizeSlug(mixed $state): ?string
    {
        $slug = Str::slug(trim((string) $state));

        return filled($slug) ? $slug : null;
    }

    protected function getOrganizationId(): string
    {
        $organizationId = $this->getOptions()['organization_id'] ?? null;

        if (blank($organizationId)) {
            throw ValidationException::withMessages([
                'organization_id' => 'Missing tenant context for import.',
            ]);
        }

        return (string) $organizationId;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $message = 'Imported '.number_format($import->successful_rows).' events.';

        if ($import->getFailedRowsCount() > 0) {
            $message .= ' '.number_format($import->getFailedRowsCount()).' rows failed validation.';
        }

        return $message;
    }
}
