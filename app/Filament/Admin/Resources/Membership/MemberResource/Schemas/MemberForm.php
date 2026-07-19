<?php

namespace App\Filament\Admin\Resources\Membership\MemberResource\Schemas;

use App\Enums\MemberStatusEnum;
use App\Filament\Forms\Components\MemberAttribute;
use App\Models\Membership\Package;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('package_id')
                            ->relationship('package', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->columnSpanFull(),
                        TextInput::make('number')
                            ->maxLength(255)
                            ->unique(table: 'membership_members', column: 'number', ignoreRecord: true)
                            ->disabled(fn(Get $get) => Package::find($get('package_id'))?->is_auto_numbering ?: false),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Select::make('status')
                            ->options(MemberStatusEnum::class)
                            ->default(MemberStatusEnum::PENDING->value)
                            ->native(false),
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->preload()
                            ->searchable()
                            ->optionsLimit(10),
                        MemberAttribute::make('Attributes')
                    ])
                    ->columnSpanFull()
                    ->columns(2)
            ]);
    }
}
