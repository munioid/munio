<?php

namespace App\Filament\Admin\Clusters\Settings\Resources\Users;

use App\Filament\Admin\Clusters\Settings;
use App\Filament\Admin\Clusters\Settings\Resources\Users\Pages;
use App\Filament\Admin\Clusters\Settings\Resources\Users\Schemas\UserForm;
use App\Filament\Admin\Clusters\Settings\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $cluster = Settings::class;

    public static function form(Schema $form): Schema
    {
        return UserForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
