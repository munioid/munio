<?php

namespace App\Filament\Admin\Resources\Store\Categories\Schemas;

use App\Models\Store\StoreCategory;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;

class StoreCategoryForm
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
                            ->afterStateUpdated(function (Set $set, ?string $state, ?StoreCategory $record) {
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
                                modifyRuleUsing: fn(Unique $rule) => $rule->where('organization_id', Filament::getTenant()->id),
                            ),
                        Textarea::make('description')
                            ->columnSpanFull(),
                        Select::make('parent_id')
                            ->label('Parent category')
                            // ignoreRecord keeps a category from being its own parent.
                            ->relationship(name: 'parent', titleAttribute: 'name', ignoreRecord: true)
                            ->searchable()
                            ->preload(),
                        TextInput::make('sort_order')
                            ->integer()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->default(true),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
