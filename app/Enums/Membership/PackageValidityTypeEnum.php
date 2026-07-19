<?php

namespace App\Enums\Membership;

use Filament\Support\Contracts\HasLabel;

enum PackageValidityTypeEnum: string implements HasLabel
{
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';
    case LIFETIME = 'lifetime';
    case CUSTOMDATE = 'custom_date';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MONTHLY => 'Monthly',
            self::YEARLY => 'Yearly',
            self::LIFETIME => 'Lifetime',
            self::CUSTOMDATE => 'Custom Date'
        };
    }
}