<?php

namespace App\Filament\Admin\Resources\Store\Tags\Pages;

use App\Filament\Admin\Resources\Store\Tags\StoreTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStoreTag extends EditRecord
{
    protected static string $resource = StoreTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
