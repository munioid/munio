<?php

namespace App\Filament\Exports;

use App\Models\Event\Event;
use App\Models\Organization\Organization;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Facades\Filament;
use Illuminate\Validation\ValidationException;

class EventExporter extends Exporter
{
    protected static ?string $model = Event::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('title')
                ->label('Title')
                ->getStateUsing(fn (Event $record): string => $record->title ?? ''),

            ExportColumn::make('slug')
                ->label('Slug')
                ->getStateUsing(fn (Event $record): string => $record->slug ?? ''),

            ExportColumn::make('content')
                ->label('Content')
                ->getStateUsing(fn (Event $record): string => $record->content ?? ''),

            ExportColumn::make('excerpt')
                ->label('Excerpt')
                ->getStateUsing(fn (Event $record): string => $record->excerpt ?? ''),

            ExportColumn::make('start_at')
                ->label('Start At')
                ->getStateUsing(fn (Event $record): ?string => $record->start_at?->format('Y-m-d')),

            ExportColumn::make('end_at')
                ->label('End At')
                ->getStateUsing(fn (Event $record): ?string => $record->end_at?->format('Y-m-d')),

            ExportColumn::make('category_slug')
                ->label('Category Slug')
                ->getStateUsing(fn (Event $record): string => $record->category?->slug ?? ''),

            ExportColumn::make('published')
                ->label('Published')
                ->getStateUsing(fn (Event $record): string => $record->published ? 'yes' : 'no'),

            ExportColumn::make('published_at')
                ->label('Published At')
                ->getStateUsing(fn (Event $record): ?string => $record->published_at?->format('Y-m-d H:i:s')),

            ExportColumn::make('pricing_type')
                ->label('Pricing Type')
                ->getStateUsing(fn (Event $record): string => $record->pricing_type?->value ?? ''),

            ExportColumn::make('price')
                ->label('Price')
                ->getStateUsing(fn (Event $record): ?string => $record->price !== null ? (string) $record->price : ''),

            ExportColumn::make('stocks')
                ->label('Stocks')
                ->getStateUsing(fn (Event $record): ?string => $record->stocks !== null ? (string) $record->stocks : ''),

            ExportColumn::make('external_link')
                ->label('External Link')
                ->getStateUsing(fn (Event $record): string => $record->external_link ?? ''),
        ];
    }

    public static function getCompletedNotificationBody($export): string
    {
        return 'Exported '.number_format($export->successful_rows).' events.';
    }

    protected function beforeQuery(): void
    {
        $this->ensureTenant();
    }

    public function getEloquentQuery()
    {
        return parent::getEloquentQuery()
            ->where('organization_id', $this->getOrganizationId())
            ->with(['category']);
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
