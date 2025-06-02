<?php

namespace App\Filament\Admin\Resources\Membership\MemberResource\Pages;

use App\Filament\Admin\Resources\Membership\MemberResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;
}