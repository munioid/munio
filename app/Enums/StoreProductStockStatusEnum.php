<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StoreProductStockStatusEnum: string implements HasColor, HasLabel
{
    case IN_STOCK = 'in_stock';
    case OUT_OF_STOCK = 'out_of_stock';
    case ON_BACKORDER = 'on_backorder';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::IN_STOCK => 'In Stock',
            self::OUT_OF_STOCK => 'Out of Stock',
            self::ON_BACKORDER => 'On Backorder',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::IN_STOCK => 'success',
            self::OUT_OF_STOCK => 'danger',
            self::ON_BACKORDER => 'warning',
        };
    }
}
