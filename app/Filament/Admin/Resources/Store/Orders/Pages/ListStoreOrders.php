<?php

namespace App\Filament\Admin\Resources\Store\Orders\Pages;

use App\Filament\Admin\Resources\Store\Orders\StoreOrderResource;
use App\Filament\Exports\OrderExporter;
use App\Filament\Exports\OrderItemExporter;
use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Actions\ExportAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;

class ListStoreOrders extends ListRecords
{
    protected static string $resource = StoreOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ActionGroup::make([
                ExportAction::make('exportOrders')
                    ->label('Export Orders')
                    ->exporter(OrderExporter::class)
                    ->options([
                        'organization_id' => Filament::getTenant()?->id,
                    ]),
                ExportAction::make('exportOrderItems')
                    ->label('Export Order Items')
                    ->exporter(OrderItemExporter::class)
                    ->options([
                        'organization_id' => Filament::getTenant()?->id,
                    ]),
            ])
                ->label('Export')
                ->button()
                ->color('info'),
        ];
    }
}
