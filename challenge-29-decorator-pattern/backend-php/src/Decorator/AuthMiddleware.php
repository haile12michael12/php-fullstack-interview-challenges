<?php

namespace App\Decorator;

use App\Component\HttpRequest;
use App\Component\HttpResponse;

class AuthMiddleware extends MiddlewareDecorator
{
    /**
     * Handle an HTTP request and return a response
     *
     * @param HttpRequest $request The HTTP request to handle
     * @return HttpResponse The HTTP response
     */
    public function handle(HttpRequest $request): HttpResponse
    {
        // Check for Authorization header
        $authHeader = $request->getHeader('Authorization');
        
        if (!$authHeader) {
            return new HttpResponse(
                401,
                ['Content-Type' => 'application/json'],
                json_encode([
                    'error' => 'Unauthorized',
                    'message' => 'Missing Authorization header'
                ])
            );
        }
        
        // Simple token validation (in a real app, this would be more complex)
        $token = str_replace('Bearer ', '', $authHeader);
        if (empty($token) || $token !== 'valid-token') {
            return new HttpResponse(
                401,
                ['Content-Type' => 'application/json'],
                json_encode([
                    'error' => 'Unauthorized',
                    'message' => 'Invalid or expired token'
                ])
            );
        }
        
        // Add user information to the request
        $request = $request->withAttribute('user', [
            'id' => 1,
            'username' => 'john_doe',
            'role' => 'admin'
        ]);
        
        // Pass the request to the next handler
        return $this->nextHandler->handle($request);
    }
}