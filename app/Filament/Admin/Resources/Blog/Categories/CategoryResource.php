<?php

namespace App\Filament\Admin\Resources\Blog\Categories;

use App\Filament\Admin\Resources\Blog\Categories\Pages;
use App\Filament\Admin\Resources\Blog\Categories\Schemas\CategoryForm;
use App\Filament\Admin\Resources\Blog\Categories\Tables\CategoriesTable;
use App\Models\Blog\Category;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static UnitEnum|string|null $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $form): Schema
    {
        return CategoryForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
