<?php

namespace App\Filament\Admin\Resources\Membership\Packages\Schemas;

use App\Enums\Membership\PackageValidityTypeEnum;
use App\Filament\Forms\Components\MunioFileUpload;
use App\Models\Membership\Attribute;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class PackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
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
                                    ->visible(fn (Get $get) => in_array($get('validity_type'), [PackageValidityTypeEnum::MONTHLY, PackageValidityTypeEnum::YEARLY])),
                                DatePicker::make('validity_end_at')
                                    ->label('End at')
                                    ->required()
                                    ->visible(fn (Get $get) => $get('validity_type') === PackageValidityTypeEnum::CUSTOMDATE),
                                Textarea::make('description')
                                    ->columnSpanFull(),
                                RichEditor::make('information')
                                    ->columnSpanFull(),
                                Toggle::make('is_auto_numbering')
                                    ->live()
                                    ->columnSpanFull(),
                                TextInput::make('format')
                                    ->visible(fn (Get $get) => $get('is_auto_numbering'))
                                    ->columnSpanFull(),
                                Callout::make('Data Variables')
                                    ->icon(Heroicon::OutlinedLightBulb)
                                    ->visible(fn (Get $get) => $get('is_auto_numbering'))
                                    ->iconColor('primary')
                                    ->info()
                                    ->footer(
                                        View::make('filament.membership.hint-variable')
                                            ->viewData([
                                                'attributes' => Attribute::query()->notPrivate()->get(),
                                            ])
                                    )
                                    ->visible(fn (Get $get) => $get('is_auto_numbering'))
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->columnSpan(4),
                        Section::make()
                            ->schema([
                                MunioFileUpload::make('cover'),
                                MunioFileUpload::make('vcard_background')
                                    ->label('Virtual Card Background'),
                                Toggle::make('is_active'),
                            ])
                            ->columnSpan(2),
                    ])
                    ->columns(6)
                    ->columnSpanFull()
                    ->contained(false),
            ]);
    }
}
