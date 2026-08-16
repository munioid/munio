<?php

namespace App\Filament\Admin\Resources\Store\Orders\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StoreOrderStatusHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'statusHistories';

    protected static ?string $title = 'Status Histories';

    /**
     * Immutable audit trail (OD-Order-2) — rows are only ever written
     * automatically by the StoreOrder model observer, so no create/edit/
     * delete actions are registered here.
     */
    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('changedBy'))
            ->columns([
                TextColumn::make('status_from')
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('status_to')
                    ->badge(),
                TextColumn::make('changedBy.name')
                    ->label('Changed by')
                    ->placeholder('System'),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([])
            ->defaultSort('created_at', 'desc');
    }
}
