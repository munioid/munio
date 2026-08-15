<?php

namespace App\Filament\Admin\Resources\Store\Products\Pages;

use App\Filament\Admin\Resources\Store\Products\StoreProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStoreProduct extends EditRecord
{
    protected static string $resource = StoreProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\ForceDeleteAction::make(),
        ];
    }
}
