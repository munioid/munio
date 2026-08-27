<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * Determines the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * The root template that is loaded on every request.
     */
    public function rootView(Request $request): string
    {
        return 'app';
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // Get organization from request if available (set by middleware)
        $organization = $request->organization ?? null;
        $theme = $request->theme ?? 'default';
        $primaryColor = $organization?->colors['primary'] ?? '#ff5c54';

        // Share view data for Blade root template
        view()->share([
            'theme' => $theme,
            'primaryColor' => $primaryColor,
        ]);

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'organization' => [
                'name' => $organization?->name ?? config('app.name', 'Munio'),
                'icon' => $organization?->icon?->getPath(),
                'favicon' => $organization?->favicon?->getPath(),
                'colors' => [
                    'primary' => $primaryColor,
                ],
            ],
            'theme' => $theme,
            'primaryColor' => $primaryColor,
            'flash' => [
                'toast' => $request->session()->get('toast'),
                'toasts' => $request->session()->get('toasts'),
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'ziggy' => function () {
                return [
                    ...(new Ziggy())->toArray(),
                    'location' => request()->url(),
                ];
            },
        ]);
    }
}
