<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ReservationStatusEnum: string implements HasLabel, HasColor
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case RECOMENDED = 'recomended';
    case APPROVED = 'approved';
    case CANCELED = 'canceled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::RECOMENDED => 'Recomended',
            self::APPROVED => 'Approved',
            self::CANCELED => 'Canceled'
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::PAID => 'success',
            self::RECOMENDED => 'info',
            self::APPROVED => 'success',
            self::CANCELED => 'danger'
        };
    }
}
