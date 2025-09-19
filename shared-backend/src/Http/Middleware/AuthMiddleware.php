<?php

declare(strict_types=1);

namespace SharedBackend\Http\Middleware;

use SharedBackend\Http\Request;
use SharedBackend\Http\Response;
use SharedBackend\Core\Logger;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * JWT Authentication middleware
 */
class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Logger $logger,
        private string $secretKey,
        private string $algorithm = 'HS256'
    ) {
    }

    public function handle(Request $request, callable $next): Response
    {
        $token = $this->extractToken($request);

        if (!$token) {
            $this->logger->warning('Authentication failed: No token provided');
            return new Response(
                new \GuzzleHttp\Psr7\Response(),
                new \GuzzleHttp\Psr7\StreamFactory()
            )->unauthorized('Authentication token required');
        }

        try {
            $payload = JWT::decode($token, new Key($this->secretKey, $this->algorithm));
            
            // Add user information to request context
            $request->addContext(['user' => $payload]);
            
            $this->logger->debug('User authenticated', [
                'user_id' => $payload->sub ?? null
            ]);

            return $next($request);
        } catch (\Exception $e) {
            $this->logger->warning('Authentication failed: Invalid token', [
                'error' => $e->getMessage()
            ]);
            
            return new Response(
                new \GuzzleHttp\Psr7\Response(),
                new \GuzzleHttp\Psr7\StreamFactory()
            )->unauthorized('Invalid authentication token');
        }
    }

    private function extractToken(Request $request): ?string
    {
        // Try Authorization header
        $authHeader = $request->getHeaderLine('Authorization');
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }

        // Try query parameter
        $token = $request->getQuery('token');
        if ($token) {
            return $token;
        }

        // Try cookie
        $token = $request->getCookie('auth_token');
        if ($token) {
            return $token;
        }

        return null;
    }
}
