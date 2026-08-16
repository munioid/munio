<?php

namespace App\Filament\Admin\Resources\Membership\Packages;

use App\Filament\Admin\Resources\Membership\Packages\Pages\CreatePackage;
use App\Filament\Admin\Resources\Membership\Packages\Pages\EditPackage;
use App\Filament\Admin\Resources\Membership\Packages\Pages\ListPackages;
use App\Filament\Admin\Resources\Membership\Packages\Schemas\PackageForm;
use App\Filament\Admin\Resources\Membership\Packages\Tables\PackagesTable;
use App\Models\Membership\Package;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static UnitEnum|string|null $navigationGroup = 'Membership';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return PackageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PackagesTable::configure($table);
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
            'index' => ListPackages::route('/'),
            'create' => CreatePackage::route('/create'),
            'edit' => EditPackage::route('/{record}/edit'),
        ];
    }
}
