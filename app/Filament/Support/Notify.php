<?php

namespace App\Filament\Support;

use Filament\Actions\Action;
use Filament\Notifications\Notification;

class Notify
{
    protected static function make(string $title, ?string $body = null, string $color = 'primary'): Notification
    {
        return Notification::make()
            ->title($title)
            ->body($body)
            ->actions([
                Action::make('close')
                    ->label('Tutup')
                    ->button()
                    ->color($color)
                    ->close(),
            ]);
    }

    public static function success(string $title, ?string $body = null): Notification
    {
        return static::make($title, $body, 'primary')
            ->success()
            ->send();
    }

    public static function danger(string $title, ?string $body = null): Notification
    {
        return static::make($title, $body, 'danger')
            ->danger()
            ->send();
    }

    public static function warning(string $title, ?string $body = null): Notification
    {
        return static::make($title, $body, 'warning')
            ->warning()
            ->send();
    }

    public static function info(string $title, ?string $body = null): Notification
    {
        return static::make($title, $body, 'info')
            ->info()
            ->send();
    }
}
