<?php

namespace Challenge02\Http\Middleware;

use Challenge02\Contracts\Auth\AuthInterface;

class AuthMiddleware
{
    private AuthInterface $auth;

    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    public function handle(): bool
    {
        $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        $token = str_replace('Bearer ', '', $token);

        if (empty($token)) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return false;
        }

        if (!$this->auth->validateToken($token)) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid token']);
            return false;
        }

        // Token is valid, continue with the request
        return true;
    }
}