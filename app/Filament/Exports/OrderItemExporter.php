<?php

namespace App\Filament\Exports;

use App\Models\Organization\Organization;
use App\Models\Store\StoreOrderItem;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Validation\ValidationException;

class OrderItemExporter extends Exporter
{
    protected static ?string $model = StoreOrderItem::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('order_number')
                ->label('Order Number')
                ->getStateUsing(fn (StoreOrderItem $record): string => $record->order?->order_number ?? ''),

            ExportColumn::make('product_name')
                ->label('Product Name'),

            ExportColumn::make('price')
                ->label('Price')
                ->getStateUsing(fn (StoreOrderItem $record): string => (string) $record->price),

            ExportColumn::make('quantity')
                ->label('Quantity')
                ->getStateUsing(fn (StoreOrderItem $record): string => (string) $record->quantity),

            ExportColumn::make('subtotal')
                ->label('Subtotal')
                ->getStateUsing(fn (StoreOrderItem $record): string => (string) $record->subtotal),
        ];
    }

    public static function getCompletedNotificationBody($export): string
    {
        return 'Exported '.number_format($export->successful_rows).' order items.';
    }

    protected function beforeQuery(): void
    {
        $this->ensureTenant();
    }

    public function getEloquentQuery(): EloquentCollection
    {
        return StoreOrderItem::query()
            ->whereHas('order', function ($query) {
                $query->where('organization_id', $this->getOrganizationId());
            })
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
