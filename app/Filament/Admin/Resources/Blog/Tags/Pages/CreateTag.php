<?php

namespace App\Filament\Admin\Resources\Blog\Tags\Pages;

use App\Filament\Admin\Resources\Blog\Tags\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTag extends CreateRecord
{
    protected static string $resource = TagResource::class;
}