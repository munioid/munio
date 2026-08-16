<?php

namespace App\Filament\Admin\Resources\Store\Products;

use App\Filament\Admin\Resources\Store\Products\Schemas\StoreProductForm;
use App\Filament\Admin\Resources\Store\Products\Tables\StoreProductsTable;
use App\Models\Store\StoreProduct;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class StoreProductResource extends Resource
{
    protected static ?string $model = StoreProduct::class;

    protected static UnitEnum|string|null $navigationGroup = 'Store';

    protected static ?string $navigationLabel = 'Products';

    protected static ?string $modelLabel = 'Product';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $form): Schema
    {
        return StoreProductForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return StoreProductsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        // Let the trashed filter decide what is visible.
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->with('category');
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
            'index' => Pages\ListStoreProducts::route('/'),
            'create' => Pages\CreateStoreProduct::route('/create'),
            'edit' => Pages\EditStoreProduct::route('/{record}/edit'),
        ];
    }
}
