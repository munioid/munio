<?php

namespace App\Filament\Admin\Resources\Membership\Members\Pages;

use App\Filament\Admin\Resources\Membership\Members\MemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}