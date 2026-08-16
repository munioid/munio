<?php

namespace App\Filament\Admin\Resources\Store\Tags;

use App\Filament\Admin\Resources\Store\Tags\Pages;
use App\Filament\Admin\Resources\Store\Tags\Schemas\StoreTagForm;
use App\Filament\Admin\Resources\Store\Tags\Tables\StoreTagsTable;
use App\Models\Store\StoreTag;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class StoreTagResource extends Resource
{
    protected static ?string $model = StoreTag::class;

    protected static UnitEnum|string|null $navigationGroup = 'Store';

    protected static ?string $navigationLabel = 'Tags';

    protected static ?string $modelLabel = 'Tag';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $form): Schema
    {
        return StoreTagForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return StoreTagsTable::configure($table);
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
            'index' => Pages\ListStoreTags::route('/'),
            'create' => Pages\CreateStoreTag::route('/create'),
            'edit' => Pages\EditStoreTag::route('/{record}/edit'),
        ];
    }
}
