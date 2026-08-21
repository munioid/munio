<?php

namespace App\Filament\Admin\Resources\Store\Products\Tables;

use App\Enums\StoreProductStockStatusEnum;
use App\Filament\Exports\StoreProductExporter;
use App\Filament\Imports\StoreProductImporter;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class StoreProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('stock_quantity')
                    ->label('Stock')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stock_status')
                    ->badge()
                    ->sortable()
                    ->color(fn (StoreProductStockStatusEnum|string|null $state): string|array|bool|null => match ($state instanceof StoreProductStockStatusEnum ? $state : StoreProductStockStatusEnum::tryFrom((string) $state)) {
                        StoreProductStockStatusEnum::IN_STOCK => 'success',
                        StoreProductStockStatusEnum::OUT_OF_STOCK => 'danger',
                        StoreProductStockStatusEnum::ON_BACKORDER => 'warning',
                        default => null,
                    }),
                TextColumn::make('weight')
                    ->suffix(' kg')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active'),
                SelectFilter::make('stock_status')
                    ->options(StoreProductStockStatusEnum::class),
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload(),
                TrashedFilter::make(),
            ])
            ->headerActions([
                Actions\ExportAction::make()
                    ->exporter(StoreProductExporter::class)
                    ->options(fn (): array => [
                        'organization_id' => Filament::getTenant()?->getKey(),
                    ]),
                Actions\ImportAction::make()
                    ->importer(StoreProductImporter::class)
                    ->chunkSize(1000)
                    ->options(fn (): array => [
                        'organization_id' => Filament::getTenant()?->getKey(),
                    ]),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
                Actions\RestoreAction::make(),
                Actions\ForceDeleteAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                    Actions\RestoreBulkAction::make(),
                    Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
