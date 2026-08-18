<?php

namespace App\Filament\Exports;

use App\Models\Blog\Post;
use App\Models\Organization\Organization;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Facades\Filament;
use Illuminate\Validation\ValidationException;

class BlogPostExporter extends Exporter
{
    protected static ?string $model = Post::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('title')
                ->label('Title')
                ->getStateUsing(fn (Post $record): string => $record->title ?? ''),

            ExportColumn::make('slug')
                ->label('Slug')
                ->getStateUsing(fn (Post $record): string => $record->slug ?? ''),

            ExportColumn::make('content')
                ->label('Content')
                ->getStateUsing(fn (Post $record): string => $record->content ?? ''),

            ExportColumn::make('excerpt')
                ->label('Excerpt')
                ->getStateUsing(fn (Post $record): string => $record->excerpt ?? ''),

            ExportColumn::make('source')
                ->label('Source')
                ->getStateUsing(fn (Post $record): string => $record->source ?? ''),

            ExportColumn::make('category_slug')
                ->label('Category Slug')
                ->getStateUsing(fn (Post $record): string => $record->category?->slug ?? ''),

            ExportColumn::make('published')
                ->label('Published')
                ->getStateUsing(fn (Post $record): string => $record->published ? 'yes' : 'no'),

            ExportColumn::make('published_at')
                ->label('Published At')
                ->getStateUsing(fn (Post $record): ?string => $record->published_at?->format('Y-m-d H:i:s')),

            ExportColumn::make('tags')
                ->label('Tags')
                ->getStateUsing(fn (Post $record): string => $record->tags->pluck('slug')->implode('|')),
        ];
    }

    public static function getCompletedNotificationBody($export): string
    {
        return 'Exported '.number_format($export->successful_rows).' posts.';
    }

    protected function beforeQuery(): void
    {
        $this->ensureTenant();
    }

    public function getEloquentQuery()
    {
        return parent::getEloquentQuery()
            ->where('organization_id', $this->getOrganizationId())
            ->with(['category', 'tags']);
    }

    protected function ensureTenant(): void
    {
        if (Filament::getTenant() instanceof Organization) {
            return;
        }

        Filament::setTenant(Organization::query()->findOrFail($this->getOrganizationId()), true);
    }

    protected function getOrganizationId(): string
    {
        $organizationId = $this->getOptions()['organization_id'] ?? null;

        if (blank($organizationId)) {
            throw ValidationException::withMessages([
                'organization_id' => 'Missing tenant context for export.',
            ]);
        }

        return (string) $organizationId;
    }
}