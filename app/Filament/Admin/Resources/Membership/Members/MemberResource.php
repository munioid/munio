<?php

namespace App\Filament\Admin\Resources\Membership\Members;

use App\Filament\Admin\Resources\Membership\Members\Pages;
use App\Filament\Admin\Resources\Membership\Members\RelationManagers;
use App\Filament\Admin\Resources\Membership\Members\Schemas\MemberForm;
use App\Filament\Admin\Resources\Membership\Members\Tables\MembersTable;
use App\Models\Membership\Member;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static UnitEnum|string|null $navigationGroup = 'Membership';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $form): Schema
    {
        return MemberForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return MembersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\AttributesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}