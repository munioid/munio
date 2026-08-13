<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->bearerToken()) {
            Auth::shouldUse('api');
        }

        if (! $request->is('api/*')) {
            Auth::shouldUse('web');
        }

        return $next($request);
    }
}
