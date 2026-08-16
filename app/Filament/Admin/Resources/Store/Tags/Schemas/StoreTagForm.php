<?php

namespace App\Filament\Admin\Resources\Store\Tags\Schemas;

use App\Models\Store\StoreTag;
use Filament\Facades\Filament;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class StoreTagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state, ?StoreTag $record) {
                                if ((! $record?->slug) || ! $state) {
                                    $set('slug', Str::slug($state));
                                }
                            }),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            // The tenant global scope does not reach validation queries,
                            // so scope the unique rule to the organization by hand.
                            ->unique(
                                ignoreRecord: true,
                                modifyRuleUsing: fn (Unique $rule) => $rule->where('organization_id', Filament::getTenant()->id),
                            ),
                        Textarea::make('description')
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->default(true),
                        TextInput::make('sort_order')
                            ->integer()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
