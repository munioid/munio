<?php

namespace App\Filament\Admin\Resources\Store\Products\Schemas;

use App\Enums\StoreProductStockStatusEnum;
use App\Filament\Forms\Components\MunioFileUpload;
use App\Models\Store\StoreProduct;
use Filament\Facades\Filament;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class StoreProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make([
                    Section::make()
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Set $set, ?string $state, ?StoreProduct $record) {
                                    if ((! $record?->slug) || ! $state) {
                                        $set('slug', Str::slug($state));
                                    }
                                }),
                            TextInput::make('slug')
                                ->required()
                                ->maxLength(255)
                                // The tenant global scope does not reach validation queries,
                                // so scope the unique rule to the organization by hand.
                                ->unique(
                                    ignoreRecord: true,
                                    modifyRuleUsing: fn(Unique $rule) => $rule
                                        ->where('organization_id', Filament::getTenant()->id)
                                        ->whereNull('deleted_at'),
                                ),
                            RichEditor::make('description')
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->columnSpan(4),
                    Section::make()
                        ->schema([
                            MunioFileUpload::make('cover')
                                ->image(),
                            Select::make('category_id')
                                ->label('Category')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('tags')
                                ->label('Tags')
                                ->relationship('tags', 'name')
                                ->multiple()
                                ->searchable()
                                ->preload(),
                            Toggle::make('is_active')
                                ->default(true),
                            TextInput::make('sort_order')
                                ->integer()
                                ->default(0)
                                ->required(),
                        ])
                        ->columnSpan(2),
                    Section::make('Pricing & stock')
                        ->schema([
                            TextInput::make('price')
                                ->required()
                                ->numeric()
                                ->minValue(0)
                                ->prefix('Rp'),
                            TextInput::make('weight')
                                ->numeric()
                                ->minValue(0)
                                ->step(0.001)
                                ->suffix('kg'),
                            TextInput::make('stock_quantity')
                                ->required()
                                ->integer()
                                ->minValue(0)
                                ->default(0),
                            Select::make('stock_status')
                                ->required()
                                ->options(StoreProductStockStatusEnum::class)
                                ->default(StoreProductStockStatusEnum::IN_STOCK)
                                ->native(false),
                        ])
                        ->columns(2)
                        ->columnSpan(6),
                ])
                    ->contained(false)
                    ->columns(6)
                    ->columnSpanFull(),
            ]);
    }
}
