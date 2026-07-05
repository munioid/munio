<?php

namespace App\Filament\Admin\Resources\Membership\MemberResource\Schemas;

use App\Enums\MemberStatusEnum;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('number')
                            ->required()
                            ->maxLength(255)
                            ->unique(table: 'membership_members', column: 'number', ignoreRecord: true),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->telRegex('/^\+?[1-9]\d{8,14}$/'),
                        Forms\Components\Textarea::make('address')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->options(MemberStatusEnum::class)
                            ->default(MemberStatusEnum::PENDING->value)
                            ->native(false),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->preload()
                            ->searchable()
                            ->optionsLimit(10)
                    ])
                    ->columnSpanFull()
                    ->columns(2)
            ]);
    }
}