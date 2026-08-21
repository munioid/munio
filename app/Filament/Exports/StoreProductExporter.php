<?php

namespace App\Filament\Exports;

use App\Models\Organization\Organization;
use App\Models\Store\StoreProduct;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Validation\ValidationException;

class StoreProductExporter extends Exporter
{
    protected static ?string $model = StoreProduct::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')
                ->label('Name')
                ->getStateUsing(fn (StoreProduct $record): string => $record->name ?? ''),

            ExportColumn::make('slug')
                ->label('Slug')
                ->getStateUsing(fn (StoreProduct $record): string => $record->slug ?? ''),

            ExportColumn::make('description')
                ->label('Description')
                ->getStateUsing(fn (StoreProduct $record): string => $record->description ?? ''),

            ExportColumn::make('price')
                ->label('Price')
                ->getStateUsing(fn (StoreProduct $record): ?string => $record->price !== null ? (string) $record->price : ''),

            ExportColumn::make('stock_quantity')
                ->label('Stock Quantity')
                ->getStateUsing(fn (StoreProduct $record): string => (string) $record->stock_quantity),

            ExportColumn::make('stock_status')
                ->label('Stock Status')
                ->getStateUsing(fn (StoreProduct $record): string => $record->stock_status?->value ?? ''),

            ExportColumn::make('weight')
                ->label('Weight')
                ->getStateUsing(fn (StoreProduct $record): ?string => $record->weight !== null ? (string) $record->weight : ''),

            ExportColumn::make('category_id')
                ->label('Category')
                ->getStateUsing(fn (StoreProduct $record): string => $record->category?->slug ?? ''),

            ExportColumn::make('tags')
                ->label('Tags')
                ->getStateUsing(fn (StoreProduct $record): string => $record->tags->pluck('slug')->join(',')),

            ExportColumn::make('is_active')
                ->label('Is Active')
                ->getStateUsing(fn (StoreProduct $record): string => $record->is_active ? '1' : '0'),

            ExportColumn::make('sort_order')
                ->label('Sort Order')
                ->getStateUsing(fn (StoreProduct $record): string => (string) $record->sort_order),
        ];
    }

    public static function getCompletedNotificationBody($export): string
    {
        return 'Exported '.number_format($export->successful_rows).' products.';
    }

    protected function beforeQuery(): void
    {
        $this->ensureTenant();
    }

    public function getEloquentQuery(): EloquentCollection
    {
        return parent::getEloquentQuery()
            ->where('organization_id', $this->getOrganizationId())
            ->with(['category', 'tags'])
            ->get();
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
