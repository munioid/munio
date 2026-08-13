<?php

namespace App\Filament\Admin\Resources\Blog\Categories\Pages;

use App\Filament\Admin\Resources\Blog\Categories\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;
}