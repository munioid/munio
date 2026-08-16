<?php

namespace App\Filament\Admin\Resources\Store\Orders\Pages;

use App\Filament\Admin\Resources\Store\Orders\StoreOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStoreOrder extends CreateRecord
{
    protected static string $resource = StoreOrderResource::class;

    /**
     * The "items" repeater saves itself to the `items` relationship after
     * the order record is created (Filament's default relationship-repeater
     * behavior), so the authoritative subtotal/total can only be computed
     * here — once the item rows actually exist in the database.
     */
    protected function afterCreate(): void
    {
        $order = $this->record;
        $subtotal = $order->items()->sum('subtotal');

        $order->update([
            'subtotal' => $subtotal,
            'total' => $subtotal + $order->shipping_cost,
        ]);
    }
}
