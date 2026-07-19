<?php

namespace App\Filament\Admin\Resources\Membership\MemberResource\Pages;

use App\Filament\Admin\Resources\Membership\MemberResource;
use App\Traits\HasMemberAttributes;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMember extends EditRecord
{
    use HasMemberAttributes;

    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->syncAttributes();
    }
}
