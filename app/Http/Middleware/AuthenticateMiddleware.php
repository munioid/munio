<?php

namespace App\Http\Middleware;

use App\Filament\Support\Notify;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class AuthenticateMiddleware extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            if (! $request->is('api/*')) {
                Notify::danger(
                    'Anda belum login',
                    'Silakan login terlebih dahulu untuk melanjutkan.'
                );
            }

            return null;
        }

        return null;
    }
}
