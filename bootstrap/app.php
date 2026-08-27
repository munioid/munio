<?php

use App\Exceptions\AuthExceptionHandler;
use App\Exceptions\ValidationExceptionHandler;
use App\Http\Middleware\AuthenticateMiddleware;
use App\Http\Middleware\CustomAuthMiddleware;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\HasTenantMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(prepend: [
            HasTenantMiddleware::class,
        ]);

        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);

        $middleware->alias([
            'auth' => AuthenticateMiddleware::class,
            'customAuth' => CustomAuthMiddleware::class,
            'hasTenant' => HasTenantMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return app(AuthExceptionHandler::class)($e);
            }
        });

        $exceptions->render(function (
            ValidationException $e
        ) {
            return app(ValidationExceptionHandler::class)
                ->render($e);
        });
    })->create();
