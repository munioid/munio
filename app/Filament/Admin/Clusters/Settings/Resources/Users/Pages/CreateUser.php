<?php

namespace App\Filament\Admin\Clusters\Settings\Resources\Users\Pages;

use App\Filament\Admin\Clusters\Settings\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
