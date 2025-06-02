<?php

namespace App\Filament\Admin\Resources\Membership;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Enums\MemberAttributeTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Membership\Attribute;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\Membership\AttributeResource\Pages;
use App\Filament\Admin\Resources\Membership\AttributeResource\RelationManagers;

class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;

    protected static ?string $navigationGroup = 'Membership';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
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
                    ->visible(fn(Get $get) => $get('type') == MemberAttributeTypeEnum::Dropdown->value),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('fieldname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('label')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_private')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_required')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttributes::route('/'),
            'create' => Pages\CreateAttribute::route('/create'),
            'edit' => Pages\EditAttribute::route('/{record}/edit'),
        ];
    }
}