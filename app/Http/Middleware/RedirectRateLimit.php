<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class RedirectRateLimit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Her IP adresi için 60 saniyede maksimum 150 istek
        $key = 'redirect:'.$request->ip();

        // Rate limiter kontrolü
        if (RateLimiter::tooManyAttempts($key, 150)) {
            return response('Too many requests.', 429);
        }

        // Her istek için sayacı arttır
        RateLimiter::hit($key, 60);

        return $next($request);
    }
}
