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
                ->rules(['required', 'string', 'max:255'])
                ->example('Summer Festival 2024'),

            ImportColumn::make('slug')
                ->label('Slug')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('summer-festival-2024'),

            ImportColumn::make('content')
                ->label('Content')
                ->ignoreBlankState()
                ->rules(['nullable', 'string'])
                ->example('Plain text event description'),

            ImportColumn::make('excerpt')
                ->label('Excerpt')
                ->ignoreBlankState()
                ->rules(['nullable', 'string', 'max:500'])
                ->example('Brief event summary'),

            ImportColumn::make('start_at')
                ->label('Start At')
                ->requiredMapping()
                ->rules(['required', 'date_format:Y-m-d'])
                ->example('2024-07-01'),

            ImportColumn::make('end_at')
                ->label('End At')
                ->requiredMapping()
                ->rules(['required', 'date_format:Y-m-d'])
                ->example('2024-07-07'),

            ImportColumn::make('category_slug')
                ->label('Category Slug')
                ->ignoreBlankState()
                ->rules(['nullable', 'string'])
                ->guess(['category', 'category_slug'])
                ->relationship('category', resolveUsing: function (string $state, EventImporter $importer): ?Category {
                    return $importer->findOrCreateCategory($state);
                })
                ->example('festival'),

            ImportColumn::make('published')
                ->label('Published')
                ->ignoreBlankState()
                ->castStateUsing(fn (mixed $state): mixed => filled($state) ? strtolower(trim((string) $state)) === 'yes' || $state === 'true' || $state === '1' : $state)
                ->rules(['nullable', 'boolean'])
                ->example('yes'),

            ImportColumn::make('published_at')
                ->label('Published At')
                ->ignoreBlankState()
                ->rules(['nullable', 'date_format:Y-m-d H:i:s'])
                ->example('2024-06-01 10:00:00'),

            ImportColumn::make('pricing_type')
                ->label('Pricing Type')
                ->ignoreBlankState()
                ->rules(['nullable', 'string'])
                ->guess(['pricing_type', 'type'])
                ->example('single | package | url'),

            ImportColumn::make('price')
                ->label('Price')
                ->ignoreBlankState()
                ->castStateUsing(fn (mixed $state): mixed => filled($state) ? (float) $state : null)
                ->rules(['nullable', 'numeric', 'min:0'])
                ->example('50000 (if single) | (leave empty if package/url)'),

            ImportColumn::make('stocks')
                ->label('Stocks')
                ->ignoreBlankState()
                ->castStateUsing(fn (mixed $state): mixed => filled($state) ? (int) $state : null)
                ->rules(['nullable', 'integer', 'min:0'])
                ->example('100 (if single) | (leave empty if package/url)'),

            ImportColumn::make('external_link')
                ->label('External Link')
                ->ignoreBlankState()
                ->rules(['nullable', 'url', 'max:255'])
                ->example('https://ticketmaster.com/event (if url) | (leave empty if single/package)'),
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
