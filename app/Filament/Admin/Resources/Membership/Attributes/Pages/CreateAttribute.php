<?php

namespace App\Filament\Admin\Resources\Membership\Attributes\Pages;

use App\Filament\Admin\Resources\Membership\Attributes\AttributeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAttribute extends CreateRecord
{
    protected static string $resource = AttributeResource::class;
}