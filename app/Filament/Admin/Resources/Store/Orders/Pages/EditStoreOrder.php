<?php

namespace App\Filament\Admin\Resources\Store\Orders\Pages;

use App\Filament\Admin\Resources\Store\Orders\StoreOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStoreOrder extends EditRecord
{
    protected static string $resource = StoreOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Order items are locked after creation, so `subtotal` is never
     * resubmitted from the form (see StoreOrderForm) — only `shipping_cost`
     * can change here. Recalculate `total` from the stored subtotal so it
     * stays consistent with the new shipping cost.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['total'] = $this->record->subtotal + ($data['shipping_cost'] ?? $this->record->shipping_cost);

        return $data;
    }
}
