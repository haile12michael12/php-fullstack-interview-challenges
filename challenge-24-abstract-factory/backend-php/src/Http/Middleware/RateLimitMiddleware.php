<?php

namespace App\Http\Middleware;

class RateLimitMiddleware
{
    protected static $requests = [];
    protected $maxRequests = 60; // Max requests per minute
    protected $window = 60; // Time window in seconds

    public function handle($request, callable $next)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $currentTime = time();
        
        // Clean up old requests
        self::$requests[$ip] = array_filter(self::$requests[$ip] ?? [], function ($timestamp) use ($currentTime) {
            return ($currentTime - $timestamp) < $this->window;
        });
        
        // Check if limit exceeded
        if (count(self::$requests[$ip] ?? []) >= $this->maxRequests) {
            http_response_code(429);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Rate limit exceeded. Try again later.'
            ]);
            exit;
        }
        
        // Add current request
        if (!isset(self::$requests[$ip])) {
            self::$requests[$ip] = [];
        }
        self::$requests[$ip][] = $currentTime;
        
        // Set rate limit headers
        header('X-RateLimit-Limit: ' . $this->maxRequests);
        header('X-RateLimit-Remaining: ' . ($this->maxRequests - count(self::$requests[$ip])));
        
        // Continue with the next middleware
        return $next($request);
    }
}