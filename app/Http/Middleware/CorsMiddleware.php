<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    public function handle($request, Closure $next)
    {
        return $next($request)
            ->header('Access-Control-Allow-Origin', 'https://your-live-server-domain.com') // Replace with your live server domain
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS') // Add the allowed HTTP methods
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization'); // Add any custom headers needed
    }
}
