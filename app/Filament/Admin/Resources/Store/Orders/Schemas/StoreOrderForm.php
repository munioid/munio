<?php

namespace App\Filament\Admin\Resources\Store\Orders\Schemas;

use App\Enums\StoreOrderStatusEnum;
use App\Models\Store\StoreProduct;
use App\Models\User;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class StoreOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                $user = $state ? User::find($state) : null;

                                $set('name', $user?->name);
                                $set('email', $user?->email);
                            })
                            ->required(),
                        TextInput::make('order_number')
                            ->disabled()
                            ->placeholder('Generated automatically')
                            ->helperText('Assigned automatically when the order is created.'),
                        TextInput::make('name')
                            ->label('Customer name')
                            ->helperText('Filled in from the selected customer — editable independently.')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Customer email')
                            ->email()
                            ->helperText('Filled in from the selected customer — editable independently.')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Customer phone')
                            ->tel(),
                        Select::make('status')
                            ->options(StoreOrderStatusEnum::class)
                            ->default(StoreOrderStatusEnum::PENDING)
                            ->native(false)
                            ->required(),
                        TextInput::make('shipping_cost')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('Rp')
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => static::recalculateOrderTotals($get, $set))
                            ->required(),
                        TextInput::make('subtotal')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->default(0)
                            ->helperText('Sum of order item subtotals — calculated automatically.'),
                        TextInput::make('total')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->default(0)
                            ->helperText('Subtotal + shipping cost — calculated automatically.'),
                        Textarea::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Order items')
                    ->description('Add the products included in this order. Items can only be changed here, while creating the order — once the order is saved they become read-only (see the "Order Items" tab).')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->hiddenLabel()
                            ->live()
                            ->afterStateUpdated(fn (Get $get, Set $set) => static::recalculateOrderTotals($get, $set))
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        $product = $state ? StoreProduct::find($state) : null;

                                        $set('product_name', $product?->name);
                                        $set('price', $product?->price);
                                        $set('subtotal', (float) ($product?->price ?? 0) * (int) ($get('quantity') ?: 1));
                                    })
                                    ->required(),
                                TextInput::make('product_name')
                                    ->label('Snapshot name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('price')
                                    ->numeric()
                                    ->minValue(0)
                                    ->prefix('Rp')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Get $get, Set $set) => $set('subtotal', (float) ($get('price') ?: 0) * (int) ($get('quantity') ?: 1)))
                                    ->required(),
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->integer()
                                    ->minValue(1)
                                    ->default(1)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Get $get, Set $set) => $set('subtotal', (float) ($get('price') ?: 0) * (int) ($get('quantity') ?: 1)))
                                    ->required(),
                                TextInput::make('subtotal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated()
                                    ->default(0),
                            ])
                            ->columns(4)
                            ->addActionLabel('Add item')
                            ->columnSpanFull(),
                    ])
                    ->visibleOn('create')
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Live preview of the order-level subtotal/total from the repeater's
     * rows. The authoritative recalculation happens server-side once items
     * are actually persisted — see CreateStoreOrder::afterCreate().
     */
    protected static function recalculateOrderTotals(Get $get, Set $set): void
    {
        $subtotal = collect($get('items') ?? [])->sum(fn (array $item) => (float) ($item['subtotal'] ?? 0));

        $set('subtotal', $subtotal);
        $set('total', $subtotal + (float) ($get('shipping_cost') ?: 0));
    }
}
