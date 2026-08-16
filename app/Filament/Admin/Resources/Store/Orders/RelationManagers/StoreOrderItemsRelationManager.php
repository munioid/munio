<?php

namespace App\Filament\Admin\Resources\Store\Orders\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StoreOrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Order Items';

    /**
     * Order items can only be added/changed/removed on the order's Create
     * page (StoreOrderForm's "items" repeater, saved before this record even
     * exists). This tab is only reachable once the order has been saved, so
     * it intentionally registers no create/edit/attach/detach actions —
     * it's a read-only view of the snapshot items (OD-Order-4).
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('product_name')
            ->modifyQueryUsing(fn (Builder $query) => $query->with('product'))
            ->columns([
                TextColumn::make('product.name')
                    ->label('Product')
                    ->placeholder('— (product removed)'),
                TextColumn::make('product_name')
                    ->label('Snapshot name'),
                TextColumn::make('price')
                    ->money('IDR'),
                TextColumn::make('quantity')
                    ->numeric(),
                TextColumn::make('subtotal')
                    ->money('IDR'),
            ])
            ->headerActions([])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
