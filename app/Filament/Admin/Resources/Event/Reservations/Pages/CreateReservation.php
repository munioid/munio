<?php

namespace App\Filament\Admin\Resources\Event\Reservations\Pages;

use App\Filament\Admin\Resources\Event\Reservations\ReservationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateReservation extends CreateRecord
{
    protected static string $resource = ReservationResource::class;
}
