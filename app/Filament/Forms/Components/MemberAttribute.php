<?php

namespace App\Filament\Forms\Components;

use App\Models\Membership\Attribute;
use Filament\Schemas\Components\Fieldset;

class MemberAttribute extends Fieldset
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->schema(fn () => $this->getAttributeFields())
            ->columnSpanFull()
            ->columns(2);
    }

    protected function getAttributeFields(): array
    {
        return Attribute::query()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn (Attribute $attribute) => $attribute->toFormComponent())
            ->all();
    }
}
