<?php

namespace App\Filament\Admin\Resources\Event\Reservations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReservationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->sortable(),
                TextColumn::make('email')
                    ->sortable(),
                TextColumn::make('event.title')
                    ->sortable(),
                TextColumn::make('package.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('price')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('quantity'),
                TextColumn::make('total')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                SelectFilter::make('event')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
