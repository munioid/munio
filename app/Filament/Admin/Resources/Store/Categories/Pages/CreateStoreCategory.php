<?php

namespace App\Filament\Admin\Resources\Store\Categories\Pages;

use App\Filament\Admin\Resources\Store\Categories\StoreCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStoreCategory extends CreateRecord
{
    protected static string $resource = StoreCategoryResource::class;
}
