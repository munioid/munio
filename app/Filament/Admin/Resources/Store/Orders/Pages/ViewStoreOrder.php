<?php

namespace App\Filament\Admin\Resources\Store\Orders\Pages;

use App\Filament\Admin\Resources\Store\Orders\StoreOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStoreOrder extends ViewRecord
{
    protected static string $resource = StoreOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
