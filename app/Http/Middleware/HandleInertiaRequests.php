<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on every request.
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'organization' => [
                'name' => config('app.name', 'Munio'),
                'icon' => null,
                'favicon' => null,
                'colors' => [
                    'primary' => '#1f2937',
                ],
            ],
            'theme' => 'default',
            'primaryColor' => '#1f2937',
        ]);
    }
}
