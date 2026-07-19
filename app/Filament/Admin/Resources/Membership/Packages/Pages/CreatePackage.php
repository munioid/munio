<?php

namespace App\Filament\Admin\Resources\Membership\Packages\Pages;

use App\Filament\Admin\Resources\Membership\Packages\PackageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePackage extends CreateRecord
{
    protected static string $resource = PackageResource::class;
}
