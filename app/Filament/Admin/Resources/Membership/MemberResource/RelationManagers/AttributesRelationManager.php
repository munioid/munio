<?php

namespace App\Filament\Admin\Resources\Membership\MemberResource\RelationManagers;

use App\Enums\MemberAttributeTypeEnum;
use App\Models\Membership\Attribute;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class AttributesRelationManager extends RelationManager
{
    protected static string $relationship = 'attributes';

    // public function form(Schema $form): Schema
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\Select::make('type')
    //                 ->options(MemberAttributeTypeEnum::class)
    //                 ->live()
    //                 ->afterStateUpdated(function ($state) {
    //                     dd($state); // atau logger($state);
    //                 }),
    //             Forms\Components\TextInput::make('value')
    //                 ->label('Value')
    //                 ->required(fn($record) => $record->is_required)
    //                 ->visible(fn(Get $get) => $get('type') == MemberAttributeTypeEnum::Text->value),
    //             Forms\Components\Select::make('value')
    //                 ->label('Value')
    //                 ->required(fn($record) => $record->is_required)
    //                 ->native(false)
    //                 ->options(fn($record) => collect($record->options)->pluck('value', 'code')->toArray())
    //                 ->visible(fn(Get $get) => $get('type') == MemberAttributeTypeEnum::Dropdown->value),
    //         ])
    //         ->columns(1);
    // }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\TextColumn::make('pivot_value')
                    ->label('Value'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelect(function (Forms\Components\Select $select) {
                        return $select
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $attribute = Attribute::find($state);

                                $set('type', $attribute?->type);
                                $set('is_required', $attribute?->is_required);
                                $set('attribute_id', $attribute?->id);
                            });
                    })
                    ->schema(fn(Actions\AttachAction $action) => [
                        $action->getRecordSelect(),
                        Forms\Components\Hidden::make('type')
                            ->live(),
                        Forms\Components\Hidden::make('is_required')
                            ->live(),
                        Forms\Components\Hidden::make('attribute_id')
                            ->live(),
                        Forms\Components\TextInput::make('value')
                            ->label('Value')
                            ->required(fn(Get $get) => $get('is_required'))
                            ->visible(fn(Get $get) => $get('type') == MemberAttributeTypeEnum::Text),
                        Forms\Components\Select::make('value')
                            ->label('Value')
                            ->required(fn(Get $get) => $get('is_required'))
                            ->native(false)
                            ->options(fn(Get $get) => collect(Attribute::find($get('attribute_id'))->options)->pluck('value', 'code')->toArray())
                            ->visible(fn(Get $get) => $get('type') == MemberAttributeTypeEnum::Dropdown),
                    ]),
            ])
            ->recordActions([
                Actions\EditAction::make()
                    ->modalWidth('xl'),
            ]);
    }
}
