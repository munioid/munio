<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PricingTypeEnum: string implements HasLabel
{
    case SINGLE = 'single';
    case PACKAGE = 'package';
    case EXTERNAL = 'external';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SINGLE => 'Single',
            self::PACKAGE => 'Package',
            self::EXTERNAL => 'External',
        };
    }
}