<?php

namespace App\Filament\Exports;

use App\Models\Organization\Organization;
use App\Models\Store\StoreOrder;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Validation\ValidationException;

class OrderExporter extends Exporter
{
    protected static ?string $model = StoreOrder::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('order_number')
                ->label('Order Number'),

            ExportColumn::make('user_id')
                ->label('User ID'),

            ExportColumn::make('name')
                ->label('Customer Name')
                ->getStateUsing(fn (StoreOrder $record): string => $record->name ?? ''),

            ExportColumn::make('email')
                ->label('Email')
                ->getStateUsing(fn (StoreOrder $record): string => $record->email ?? ''),

            ExportColumn::make('phone')
                ->label('Phone')
                ->getStateUsing(fn (StoreOrder $record): string => $record->phone ?? ''),

            ExportColumn::make('status')
                ->label('Status')
                ->getStateUsing(fn (StoreOrder $record): string => $record->status?->value ?? ''),

            ExportColumn::make('subtotal')
                ->label('Subtotal')
                ->getStateUsing(fn (StoreOrder $record): string => (string) $record->subtotal),

            ExportColumn::make('shipping_cost')
                ->label('Shipping Cost')
                ->getStateUsing(fn (StoreOrder $record): string => (string) $record->shipping_cost),

            ExportColumn::make('total')
                ->label('Total')
                ->getStateUsing(fn (StoreOrder $record): string => (string) $record->total),

            ExportColumn::make('notes')
                ->label('Notes')
                ->getStateUsing(fn (StoreOrder $record): string => $record->notes ?? ''),
        ];
    }

    public static function getCompletedNotificationBody($export): string
    {
        return 'Exported '.number_format($export->successful_rows).' orders.';
    }

    protected function beforeQuery(): void
    {
        $this->ensureTenant();
    }

    public function getEloquentQuery(): EloquentCollection
    {
        return parent::getEloquentQuery()
            ->where('organization_id', $this->getOrganizationId())
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
