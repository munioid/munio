<?php

namespace App\Filament\Admin\Resources\Membership\Attributes;

use App\Filament\Admin\Resources\Membership\Attributes\Pages;
use App\Filament\Admin\Resources\Membership\Attributes\Schemas\AttributeForm;
use App\Filament\Admin\Resources\Membership\Attributes\Schemas\AttributeTable;
use App\Models\Membership\Attribute;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;

    protected static UnitEnum|string|null $navigationGroup = 'Membership';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $form): Schema
    {
        return AttributeForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return AttributeTable::configure($table);
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
            'index' => Pages\ListAttributes::route('/'),
            'create' => Pages\CreateAttribute::route('/create'),
            'edit' => Pages\EditAttribute::route('/{record}/edit'),
        ];
    }
}