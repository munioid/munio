<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Event\Reservations\ReservationResource;
use App\Models\Event\Reservation;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentReservationsWidget extends BaseWidget
{
    protected static ?int $sort = 13;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Recent Reservations';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Reservation::query()->latest()
            )
            ->columns([
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('event.title')
                    ->label('Event')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('quantity'),
                TextColumn::make('status')
                    ->badge(),
            ])
            ->recordUrl(fn (Reservation $record) => ReservationResource::getUrl('edit', ['record' => $record]))
            ->defaultSort('created_at', 'desc')
            ->paginated([5]);
    }
}
