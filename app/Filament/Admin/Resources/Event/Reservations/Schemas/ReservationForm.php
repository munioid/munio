<?php

namespace App\Filament\Admin\Resources\Event\Reservations\Schemas;

use App\Enums\ReservationStatusEnum;
use App\Enums\PricingTypeEnum;
use App\Models\Event\Event;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Select::make('event_id')
                            ->required()
                            ->relationship(name: 'event', titleAttribute: 'title')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->columnSpanFull()
                            ->disabled(fn(string $operation) => $operation === 'edit'),
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->email()
                            ->required(),
                        TextInput::make('quantity')
                            ->required()
                            ->numeric()
                            ->default(1)
                            ->disabled(fn(string $operation) => $operation === 'edit'),
                        Select::make('package_id')
                            ->relationship(
                                name: 'package',
                                titleAttribute: 'name',
                                modifyQueryUsing: function ($query, Get $get) {
                                    $query->where('event_id', $get('event_id'));
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->visible(fn(Get $get) => Event::find($get('event_id'))?->pricing_type == PricingTypeEnum::PACKAGE)
                            ->disabled(fn(string $operation) => $operation === 'edit'),
                        Select::make('user_id')
                            ->relationship(name: 'user', titleAttribute: 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('status')
                            ->required()
                            ->options(ReservationStatusEnum::class)
                            ->native(false)
                            ->default(ReservationStatusEnum::PENDING)
                    ])
                    ->columns(2)
                    ->columnSpanFull()
            ]);
    }
}
