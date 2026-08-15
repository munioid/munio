<?php

namespace App\Filament\Admin\Resources\Store\Tags\Pages;

use App\Filament\Admin\Resources\Store\Tags\StoreTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStoreTags extends ListRecords
{
    protected static string $resource = StoreTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
