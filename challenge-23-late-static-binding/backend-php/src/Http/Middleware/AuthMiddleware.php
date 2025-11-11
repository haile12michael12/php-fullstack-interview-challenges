<?php

namespace App\Http\Middleware;

class AuthMiddleware
{
    public function handle($request, callable $next)
    {
        // Simple authentication check
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        
        if (empty($authHeader)) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        
        // In a real application, you would validate the token
        // For this demo, we'll just check if it starts with "Bearer "
        if (strpos($authHeader, 'Bearer ') !== 0) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid token format']);
            exit;
        }
        
        return $next($request);
    }
}