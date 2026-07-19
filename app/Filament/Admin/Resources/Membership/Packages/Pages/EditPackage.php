<?php

namespace App\Filament\Admin\Resources\Membership\Packages\Pages;

use App\Filament\Admin\Resources\Membership\Packages\PackageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPackage extends EditRecord
{
    protected static string $resource = PackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
