<?php

namespace App\Http\Middleware;

class JsonResponseMiddleware
{
    public function handle($request, callable $next)
    {
        // Set JSON response header
        header('Content-Type: application/json');
        
        return $next($request);
    }
}