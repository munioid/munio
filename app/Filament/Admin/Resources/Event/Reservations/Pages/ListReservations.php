<?php

namespace App\Filament\Admin\Resources\Event\Reservations\Pages;

use App\Filament\Admin\Resources\Event\Reservations\ReservationResource;
use App\Filament\Exports\ReservationExporter;
use Filament\Actions\CreateAction;
use Filament\Actions\ExportAction;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListReservations extends ListRecords
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            ExportAction::make()
                ->exporter(ReservationExporter::class)
                ->label('Export')
                ->icon(Heroicon::ArrowDownTray)
                ->color('info')
                ->options([
                    'organization_id' => Filament::getTenant()?->id,
                ]),
        ];
    }
}
