<?php

namespace App\Filament\Admin\Resources\Store\Orders;

use App\Filament\Admin\Resources\Store\Orders\Schemas\StoreOrderForm;
use App\Filament\Admin\Resources\Store\Orders\Tables\StoreOrdersTable;
use App\Models\Store\StoreOrder;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class StoreOrderResource extends Resource
{
    protected static ?string $model = StoreOrder::class;

    protected static UnitEnum|string|null $navigationGroup = 'Store';

    protected static ?string $navigationLabel = 'Orders';

    protected static ?string $modelLabel = 'Order';

    protected static ?string $recordTitleAttribute = 'order_number';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $form): Schema
    {
        return StoreOrderForm::configure($form);
    }

    public static function table(Table $table): Table
    {
        return StoreOrdersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\StoreOrderItemsRelationManager::class,
            RelationManagers\StoreOrderStatusHistoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStoreOrders::route('/'),
            'create' => Pages\CreateStoreOrder::route('/create'),
            'edit' => Pages\EditStoreOrder::route('/{record}/edit'),
        ];
    }
}
