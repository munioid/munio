<?php

namespace App\Http\Middleware;

use App\Models\Organization\Organization;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnboardingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Organization::exists()) {
            return redirect()->to('/admin');
        }

        return $next($request);
    }
}
