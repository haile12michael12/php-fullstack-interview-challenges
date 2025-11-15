<?php

namespace App\Http\Middleware;

class AuthMiddleware
{
    public function handle($request, callable $next)
    {
        // Get the Authorization header
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        
        if (empty($authHeader)) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Authorization header is required'
            ]);
            exit;
        }
        
        // Check if it's a Bearer token
        if (strpos($authHeader, 'Bearer ') !== 0) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid authorization header format'
            ]);
            exit;
        }
        
        // Extract the token
        $token = substr($authHeader, 7);
        
        // In a real implementation, you would validate the JWT token
        // For this demo, we'll just check if it's not empty
        if (empty($token)) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid token'
            ]);
            exit;
        }
        
        // Add user info to the request
        $request['user'] = $this->decodeToken($token);
        
        // Continue with the next middleware
        return $next($request);
    }

    protected function decodeToken($token)
    {
        // In a real implementation, you would use a proper JWT library
        // For this demo, we'll decode our simple token
        try {
            $payload = json_decode(base64_decode($token), true);
            return $payload;
        } catch (\Exception $e) {
            return null;
        }
    }
}