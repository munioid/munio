<?php

namespace App\Filament\Admin\Resources\Store\Categories;

use App\Filament\Admin\Resources\Store\Categories\Schemas\StoreCategoryForm;
use App\Filament\Admin\Resources\Store\Categories\Tables\StoreCategoriesTable;
use App\Models\Store\StoreCategory;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class StoreCategoryResource extends Resource
{
    protected static ?string $model = StoreCategory::class;

    protected static UnitEnum|string|null $navigationGroup = 'Store';

    protected static ?string $navigationLabel = 'Categories';

    protected static ?string $modelLabel = 'Category';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $form): Schema
    {
        return StoreCategoryForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return StoreCategoriesTable::configure($table);
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
            'index' => Pages\ListStoreCategories::route('/'),
            'create' => Pages\CreateStoreCategory::route('/create'),
            'edit' => Pages\EditStoreCategory::route('/{record}/edit'),
        ];
    }
}
