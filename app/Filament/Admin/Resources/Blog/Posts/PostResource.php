<?php

namespace App\Filament\Admin\Resources\Blog\Posts;

use App\Filament\Admin\Resources\Blog\Posts\Pages;
use App\Filament\Admin\Resources\Blog\Posts\Schemas\PostForm;
use App\Filament\Admin\Resources\Blog\Posts\Schemas\PostTable;
use App\Models\Blog\Post;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static UnitEnum|string|null $navigationGroup = 'Blog';
    protected static ?int $navigationSort = 1;


    public static function form(Schema $form): Schema
    {
        return PostForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return PostTable::configure($table);
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
