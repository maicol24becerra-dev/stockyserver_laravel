<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoCacheHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Headers para impedir caché
        $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, proxy-revalidate, max-age=0');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');
        $response->header('Surrogate-Control', 'no-store');
        $response->header('X-Content-Type-Options', 'nosniff');
        $response->header('X-Frame-Options', 'DENY');
        $response->header('X-XSS-Protection', '1; mode=block');

        return $response;
    }
}
