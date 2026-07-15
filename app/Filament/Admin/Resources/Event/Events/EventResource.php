<?php

namespace App\Filament\Admin\Resources\Event\Events;

use App\Filament\Admin\Resources\Event\Events\Pages\CreateEvent;
use App\Filament\Admin\Resources\Event\Events\Pages\EditEvent;
use App\Filament\Admin\Resources\Event\Events\Pages\ListEvents;
use App\Filament\Admin\Resources\Event\Events\Schemas\EventForm;
use App\Filament\Admin\Resources\Event\Events\Tables\EventsTable;
use App\Models\Event\Event;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static UnitEnum|string|null $navigationGroup = 'Event';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
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
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}
