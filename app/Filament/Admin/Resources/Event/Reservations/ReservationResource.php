<?php

namespace App\Filament\Admin\Resources\Event\Reservations;

use App\Filament\Admin\Resources\Event\Reservations\Pages\CreateReservation;
use App\Filament\Admin\Resources\Event\Reservations\Pages\EditReservation;
use App\Filament\Admin\Resources\Event\Reservations\Pages\ListReservations;
use App\Filament\Admin\Resources\Event\Reservations\Schemas\ReservationForm;
use App\Filament\Admin\Resources\Event\Reservations\Tables\ReservationsTable;
use App\Models\Event\Reservation;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;
    
    protected static UnitEnum|string|null $navigationGroup = 'Event';
    
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ReservationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReservationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReservations::route('/'),
            'create' => CreateReservation::route('/create'),
            'edit' => EditReservation::route('/{record}/edit'),
        ];
    }
}
