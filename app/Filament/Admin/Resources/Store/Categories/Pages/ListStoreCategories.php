<?php

namespace App\Filament\Admin\Resources\Store\Categories\Pages;

use App\Filament\Admin\Resources\Store\Categories\StoreCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStoreCategories extends ListRecords
{
    protected static string $resource = StoreCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
