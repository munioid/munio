<?php

namespace App\Filament\Admin\Resources\Store\Orders\Pages;

use App\Filament\Admin\Resources\Store\Orders\StoreOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStoreOrders extends ListRecords
{
    protected static string $resource = StoreOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
