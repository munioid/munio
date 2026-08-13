<?php

namespace App\Filament\Admin\Resources\Membership\Attributes\Pages;

use App\Filament\Admin\Resources\Membership\Attributes\AttributeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttribute extends EditRecord
{
    protected static string $resource = AttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}