<?php

namespace App\Filament\Admin\Resources\Membership\Attributes\Pages;

use App\Filament\Admin\Resources\Membership\Attributes\AttributeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttributes extends ListRecords
{
    protected static string $resource = AttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
