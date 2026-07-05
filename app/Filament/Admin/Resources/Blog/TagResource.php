<?php

namespace App\Filament\Admin\Resources\Blog;

use App\Filament\Admin\Resources\Blog\TagResource\Schemas\TagTable;
use App\Filament\Admin\Resources\Blog\TagResource\Pages;
use App\Filament\Admin\Resources\Blog\TagResource\Schemas\TagForm;
use App\Models\Blog\Tag;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static UnitEnum|string|null $navigationGroup = 'Blog';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $form): Schema
    {
        return TagForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return TagTable::configure($table);
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
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }
}
