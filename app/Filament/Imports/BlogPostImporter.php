<?php

namespace App\Filament\Imports;

use App\Models\Blog\Post;
use App\Models\Blog\Category;
use App\Models\Blog\Tag;
use App\Models\Organization\Organization;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BlogPostImporter extends Importer
{
    protected static ?string $model = Post::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->label('Title')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('My First Post'),

            ImportColumn::make('slug')
                ->label('Slug')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('my-first-post'),

            ImportColumn::make('content')
                ->label('Content')
                ->ignoreBlankState()
                ->rules(['nullable', 'string'])
                ->example('Plain text content here'),

            ImportColumn::make('excerpt')
                ->label('Excerpt')
                ->ignoreBlankState()
                ->rules(['nullable', 'string'])
                ->example('Brief excerpt'),

            ImportColumn::make('source')
                ->label('Source')
                ->ignoreBlankState()
                ->rules(['nullable', 'string', 'max:255'])
                ->example('Source name'),

            ImportColumn::make('category_slug')
                ->label('Category Slug')
                ->requiredMapping()
                ->rules(['required', 'string'])
                ->guess(['category', 'category_slug'])
                ->relationship('category', resolveUsing: function (string $state, BlogPostImporter $importer): ?Category {
                    return $importer->findOrCreateCategory($state);
                })
                ->example('tech-news'),

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
                ->example('2024-01-15 10:30:00'),

            ImportColumn::make('tags')
                ->label('Tags')
                ->ignoreBlankState()
                ->guess(['tag', 'tags'])
                ->relationship('tags', resolveUsing: function (array $state, BlogPostImporter $importer): \Illuminate\Database\Eloquent\Collection {
                    return $importer->findOrCreateTags($state);
                })
                ->multiple('|')
                ->saveRelationshipsUsing(function (Post $record, array $state, BlogPostImporter $importer): void {
                    $record->tags()->sync($importer->findOrCreateTags($state)->modelKeys());
                })
                ->example('tech|news|guidelines'),
        ];
    }

    public function resolveRecord(): ?Model
    {
        $slug = $this->normalizeSlug($this->data['slug'] ?? null);

        if (blank($slug)) {
            return new Post;
        }

        $record = Post::query()
            ->where('organization_id', $this->getOrganizationId())
            ->where('slug', $slug)
            ->first();

        return $record ?: new Post;
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

    /**
     * @param  array<int, string>  $state
     */
    public function findOrCreateTags(array $state): \Illuminate\Database\Eloquent\Collection
    {
        $organizationId = $this->getOrganizationId();
        $this->ensureTenant();

        $tags = collect($state)
            ->map(fn (string $slug): ?string => $this->normalizeSlug($slug))
            ->filter()
            ->unique()
            ->map(function (string $slug) use ($organizationId): Tag {
                $tag = Tag::withoutGlobalScopes()
                    ->where('organization_id', $organizationId)
                    ->where('slug', $slug)
                    ->first();

                if ($tag) {
                    return $tag;
                }

                $tag = new Tag;
                $tag->forceFill([
                    'organization_id' => $organizationId,
                    'name' => Str::headline($slug),
                    'slug' => $slug,
                    'description' => null,
                ]);
                $tag->save();

                return $tag;
            })
            ->values()
            ->all();

        return new \Illuminate\Database\Eloquent\Collection($tags);
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
        $message = 'Imported '.number_format($import->successful_rows).' posts.';

        if ($import->getFailedRowsCount() > 0) {
            $message .= ' '.number_format($import->getFailedRowsCount()).' rows failed validation.';
        }

        return $message;
    }
}