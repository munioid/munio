<?php

namespace App\Filament\Admin\Resources\Blog\Posts\Pages;

use App\Filament\Admin\Resources\Blog\Posts\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
}