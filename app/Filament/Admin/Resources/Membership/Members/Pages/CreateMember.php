<?php

namespace App\Filament\Admin\Resources\Membership\Members\Pages;

use App\Filament\Admin\Resources\Membership\Members\MemberResource;
use App\Traits\HasMemberAttributes;
use Filament\Resources\Pages\CreateRecord;

class CreateMember extends CreateRecord
{
    use HasMemberAttributes;

    protected static string $resource = MemberResource::class;

    protected function afterCreate(): void
    {
        $this->syncAttributes();
    }
}
