<?php

namespace App\Filament\Admin\Resources\Blog\CategoryResource\Pages;

use App\Filament\Admin\Resources\Blog\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}