<?php

namespace App\Filament\Admin\Resources\Store\Orders\Pages;

use App\Filament\Admin\Resources\Store\Orders\StoreOrderResource;
use App\Filament\Exports\OrderExporter;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListStoreOrders extends ListRecords
{
    protected static string $resource = StoreOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ExportAction::make()
                ->exporter(OrderExporter::class)
                ->label('Export')
                ->icon(Heroicon::ArrowDownTray)
                ->color('info')
                ->options(fn (): array => ['organization_id' => Filament::getTenant()?->id]),
        ];
    }
}
