<?php

namespace App\Filament\Admin\Resources\Event\Reservations\Pages;

use App\Filament\Admin\Resources\Event\Reservations\ReservationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReservations extends ListRecords
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
