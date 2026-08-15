<?php

namespace App\Filament\Admin\Resources\Store\Products\Pages;

use App\Filament\Admin\Resources\Store\Products\StoreProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStoreProduct extends CreateRecord
{
    protected static string $resource = StoreProductResource::class;
}
