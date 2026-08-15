<?php

namespace App\Filament\Admin\Resources\Store\Categories\Pages;

use App\Filament\Admin\Resources\Store\Categories\StoreCategoryResource;
use App\Filament\Admin\Resources\Store\Categories\Tables\StoreCategoriesTable;
use Filament\Resources\Pages\EditRecord;

class EditStoreCategory extends EditRecord
{
    protected static string $resource = StoreCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Same cascade warning as the table row action.
            StoreCategoriesTable::deleteAction(),
        ];
    }
}
