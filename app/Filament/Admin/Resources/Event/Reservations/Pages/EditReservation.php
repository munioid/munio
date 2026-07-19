<?php

namespace App\Filament\Admin\Resources\Event\Reservations\Pages;

use App\Filament\Admin\Resources\Event\Reservations\ReservationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReservation extends EditRecord
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
