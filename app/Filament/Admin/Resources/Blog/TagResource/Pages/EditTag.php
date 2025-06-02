<?php

namespace App\Filament\Admin\Resources\Blog\TagResource\Pages;

use App\Filament\Admin\Resources\Blog\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTag extends EditRecord
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}