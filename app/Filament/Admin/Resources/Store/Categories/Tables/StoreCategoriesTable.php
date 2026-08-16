<?php

namespace App\Filament\Admin\Resources\Store\Categories\Tables;

use App\Models\Store\StoreCategory;
use Filament\Actions;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class StoreCategoriesTable
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
                TextColumn::make('parent.name')
                    ->label('Parent')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('children_count')
                    ->label('Sub-categories')
                    ->counts('children')
                    ->sortable(),
                TextColumn::make('products_count')
                    ->label('Products')
                    ->counts('products')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->sortable(),
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
                SelectFilter::make('parent_id')
                    ->label('Parent')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                static::deleteAction(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->modalDescription(
                            'Deleting a category also deletes every product inside it — permanently, '
                                .'not as a soft delete. Sub-categories are kept and become top-level.'
                        ),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function deleteAction(): Actions\DeleteAction
    {
        return Actions\DeleteAction::make()
            ->requiresConfirmation()
            ->modalHeading('Delete category?')
            ->modalDescription(function (StoreCategory $record): string {
                $products = $record->products()->withTrashed()->count();

                if (! $products) {
                    return 'Are you sure you want to delete this category? This cannot be undone.';
                }

                return trans_choice(
                    'This category has :count product|This category has :count products',
                    $products,
                    ['count' => $products],
                ).' that will be permanently deleted along with it — not soft-deleted, and not recoverable. '
                    .'Move them to another category first if you want to keep them.';
            });
    }
}
