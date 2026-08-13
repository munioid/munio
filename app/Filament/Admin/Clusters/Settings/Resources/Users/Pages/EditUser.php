<?php

namespace App\Filament\Admin\Clusters\Settings\Resources\Users\Pages;

use App\Filament\Admin\Clusters\Settings\Resources\Users\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
