<?php

namespace App\Http\Middleware;

use App\Models\Organization\Organization;
use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasTenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        // default.munio.test
        $subdomain = explode('.', $host)[0];

        $organization = Organization::query()
            ->where('code', $subdomain)
            ->first();

        if (! $organization) {
            abort(404, 'Not found.');
        }

        Filament::setTenant($organization, true);

        return $next($request);
    }
}
