<?php

namespace App\Filament\Admin\Resources\Blog\Posts\Schemas;

use App\Filament\Forms\Components\MunioFileUpload;
use App\Models\Blog\Post;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Set $set, ?string $state, ?Post $record) {
                                        if (! $record?->slug or ! $state) {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\RichEditor::make('content')
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('excerpt')
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('source')
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(4),
                        Section::make()
                            ->schema([
                                MunioFileUpload::make('cover')
                                    ->image(),
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->preload()
                                    ->searchable(),
                                Forms\Components\Select::make('tags')
                                    ->relationship('tags', 'name')
                                    ->preload()
                                    ->multiple()
                                    ->searchable(),
                                Forms\Components\Toggle::make('published')
                                    ->reactive(),
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->native(false)
                                    ->visible(fn (Get $get) => $get('published')),
                            ])
                            ->columnSpan(2),
                    ])
                    ->columnSpanFull()
                    ->columns(6)
                    ->contained(false),
            ]);
    }
}
