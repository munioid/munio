<?php

namespace App\Filament\Admin\Resources\Event\Events\Schemas;

use App\Enums\PricingTypeEnum;
use App\Filament\Forms\Components\MunioFileUpload;
use App\Models\Event\Event;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make([
                    Section::make()
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Set $set, ?string $state, ?Event $record) {
                                    if (!$record?->slug or !$state) {
                                        $set('slug', Str::slug($state));
                                    }
                                }),
                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\RichEditor::make('content')
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('excerpt')
                                ->columnSpanFull(),
                            Forms\Components\DatePicker::make('start_at'),
                            Forms\Components\DatePicker::make('end_at'),
                        ])
                        ->columns(2)
                        ->columnSpan(4),
                    Section::make()
                        ->schema([
                            MunioFileUpload::make('covers')
                                ->multiple()
                                ->image(),
                            Forms\Components\Select::make('category_id')
                                ->relationship('category', 'name')
                                ->preload()
                                ->searchable(),
                            Forms\Components\Toggle::make('is_published')
                                ->reactive(),
                            Forms\Components\DateTimePicker::make('published_at')
                                ->native(false)
                                ->visible(fn(Get $get) => $get('is_published')),
                        ])
                        ->columnSpan(2),
                    Tabs::make()
                        ->schema([
                            Tabs\Tab::make('Pricing')
                                ->schema([
                                    ToggleButtons::make('pricing_type')
                                        ->label('Type')
                                        ->options(PricingTypeEnum::class)
                                        ->inline()
                                        ->live()
                                        ->default(PricingTypeEnum::SINGLE)
                                        ->columnSpanFull(),
                                    TextInput::make('price')
                                        ->numeric()
                                        ->hidden(fn(Get $get) => $get('pricing_type') == PricingTypeEnum::PACKAGE),
                                    TextInput::make('stocks')
                                        ->numeric()
                                        ->visible(fn(Get $get) => $get('pricing_type') == PricingTypeEnum::SINGLE),
                                    TextInput::make('external_link')
                                        ->visible(fn(Get $get) => $get('pricing_type') == PricingTypeEnum::EXTERNAL)
                                ])
                        ])
                        ->columns(2)
                        ->columnSpan(4)
                ])
                    ->contained(false)
                    ->columns(6)
                    ->columnSpanFull(),
            ]);
    }
}
