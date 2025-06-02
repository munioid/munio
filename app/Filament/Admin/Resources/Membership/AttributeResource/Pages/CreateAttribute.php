<?php

namespace App\Filament\Admin\Resources\Membership\AttributeResource\Pages;

use App\Filament\Admin\Resources\Membership\AttributeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAttribute extends CreateRecord
{
    protected static string $resource = AttributeResource::class;
}