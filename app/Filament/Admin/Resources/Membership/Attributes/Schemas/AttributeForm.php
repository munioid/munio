<?php

namespace App\Filament\Admin\Resources\Membership\Attributes\Schemas;

use App\Enums\MemberAttributeTypeEnum;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class AttributeForm
{
    public static function configure(Schema $schema): Schema
    {
         return $schema
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('fieldname')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('label')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->required()
                            ->options(MemberAttributeTypeEnum::class)
                            ->default(MemberAttributeTypeEnum::Text->value)
                            ->native(false)
                            ->reactive(),
                        Forms\Components\Textarea::make('notes'),
                        Forms\Components\Toggle::make('is_private'),
                        Forms\Components\Toggle::make('is_required'),
                        Forms\Components\Repeater::make('options')
                            ->required()
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->required(),
                                Forms\Components\TextInput::make('value')
                                    ->required(),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->visible(fn(Get $get) => $get('type') == MemberAttributeTypeEnum::Dropdown),
                    ])
                    ->columnSpanFull()
                    ->columns(2)

            ]);
    }
}