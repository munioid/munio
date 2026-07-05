<?php

namespace App\Filament\Admin\Resources\Membership\MemberResource\RelationManagers;

use App\Enums\MemberAttributeTypeEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttributesRelationManager extends RelationManager
{
    protected static string $relationship = 'attributes';

    // public function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Forms\Components\TextInput::make('value')
    //                 ->label('Value')
    //                 ->required(fn($record) => $record->is_required)
    //                 ->hidden(fn($record) => $record->type == MemberAttributeTypeEnum::Dropdown),
    //             Forms\Components\Select::make('value')
    //                 ->label('Value')
    //                 ->required(fn($record) => $record->is_required)
    //                 ->native(false)
    //                 ->options(fn($record) => collect($record->options)->pluck('value', 'code')->toArray())
    //                 ->visible(fn($record) => $record->type == MemberAttributeTypeEnum::Dropdown),
    //         ])
    //         ->columns(1);
    // }

    // public function table(Table $table): Table
    // {
    //     return $table
    //         ->recordTitleAttribute('label')
    //         ->columns([
    //             Tables\Columns\TextColumn::make('label'),
    //             Tables\Columns\TextColumn::make('pivot_value')
    //                 ->label('Value'),
    //         ])
    //         ->filters([
    //             //
    //         ])
    //         ->headerActions([
    //             Tables\Actions\AttachAction::make()
    //                 ->preloadRecordSelect(),
    //         ])
    //         ->actions([
    //             Tables\Actions\EditAction::make()
    //                 ->modalWidth('xl'),
    //         ])
    //         ->bulkActions([
    //             // Tables\Actions\BulkActionGroup::make([
    //             //     Tables\Actions\DeleteBulkAction::make(),
    //             // ]),
    //         ]);
    // }
}