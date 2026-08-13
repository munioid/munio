<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Event\Events\EventResource;
use App\Models\Event\Event;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UpcomingEventsWidget extends BaseWidget
{
    protected static ?int $sort = 11;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Upcoming Events';

    public function table(Table $table): Table
    {
        $today = today();

        return $table
            ->query(
                Event::query()
                    ->withCount('reservations')
                    ->where(function ($query) use ($today) {
                        $query->whereDate('start_at', '>=', $today)
                            ->orWhereDate('end_at', '>=', $today);
                    })
            )
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('start_at')
                    ->label('Date & Time')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->state(fn (Event $record) => match (true) {
                        $record->start_at?->isFuture() => 'Upcoming',
                        default => 'Ongoing',
                    })
                    ->color(fn (string $state) => match ($state) {
                        'Upcoming' => 'info',
                        default => 'warning',
                    }),
                TextColumn::make('reservations_count')
                    ->label('Reservations'),
            ])
            ->recordUrl(fn (Event $record) => EventResource::getUrl('edit', ['record' => $record]))
            ->defaultSort('start_at')
            ->paginated([5]);
    }
}
