<?php

namespace App\Filament\Admin\Resources\Membership\Packages\Schemas;

use App\Enums\Membership\PackageValidityTypeEnum;
use App\Models\Membership\Attribute;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('price')
                            ->numeric(),
                        Select::make('validity_type')
                            ->required()
                            ->options(PackageValidityTypeEnum::class)
                            ->default(PackageValidityTypeEnum::LIFETIME)
                            ->native(false)
                            ->live(),
                        TextInput::make('validity_amount')
                            ->required()
                            ->numeric()
                            ->visible(fn(Get $get) => in_array($get('validity_type'), [PackageValidityTypeEnum::MONTHLY, PackageValidityTypeEnum::YEARLY])),
                        DatePicker::make('validity_end_at')
                            ->label('End at')
                            ->required()
                            ->visible(fn(Get $get) => $get('validity_type') === PackageValidityTypeEnum::CUSTOMDATE),
                        Toggle::make('is_active')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Tabs::make()
                    ->tabs([
                        Tabs\Tab::make('Information')
                            ->schema([
                                Textarea::make('description'),
                                RichEditor::make('information'),
                            ]),
                        Tabs\Tab::make('Numbering')
                            ->schema([
                                Toggle::make('is_auto_numbering')
                                    ->live(),
                                TextInput::make('format')
                                    ->visible(fn(Get $get) => $get('is_auto_numbering')),
                                Callout::make('Data Variables')
                                    ->icon(\Filament\Support\Icons\Heroicon::OutlinedLightBulb)
                                    ->visible(fn(Get $get) => $get('is_auto_numbering'))
                                    ->iconColor('primary')
                                    ->info()
                                    ->footer(
                                        View::make('filament.membership.hint-variable')
                                            ->viewData([
                                                'attributes' => Attribute::query()->notPrivate()->get()
                                            ])
                                    )
                            ])
                    ])
                    ->columnSpanFull()
            ]);
    }
}
