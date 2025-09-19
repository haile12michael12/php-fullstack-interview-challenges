<?php

declare(strict_types=1);

namespace SharedBackend\Http\Middleware;

use SharedBackend\Http\Request;
use SharedBackend\Http\Response;
use SharedBackend\Core\Config;

/**
 * CORS middleware for handling cross-origin requests
 */
class CorsMiddleware implements MiddlewareInterface
{
    public function __construct(private Config $config)
    {
    }

    public function handle(Request $request, callable $next): Response
    {
        $response = $next($request);

        $corsConfig = $this->config->get('cors', []);
        
        $allowedOrigins = $corsConfig['allowed_origins'] ?? ['*'];
        $allowedMethods = $corsConfig['allowed_methods'] ?? ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'];
        $allowedHeaders = $corsConfig['allowed_headers'] ?? ['*'];
        $exposedHeaders = $corsConfig['exposed_headers'] ?? [];
        $maxAge = $corsConfig['max_age'] ?? 86400;
        $supportsCredentials = $corsConfig['supports_credentials'] ?? false;

        $origin = $request->getHeaderLine('Origin');
        
        // Handle preflight requests
        if ($request->getMethod() === 'OPTIONS') {
            $response->withHeader('Access-Control-Allow-Origin', $this->getAllowedOrigin($origin, $allowedOrigins));
            $response->withHeader('Access-Control-Allow-Methods', implode(', ', $allowedMethods));
            $response->withHeader('Access-Control-Allow-Headers', implode(', ', $allowedHeaders));
            $response->withHeader('Access-Control-Max-Age', (string)$maxAge);
            
            if ($supportsCredentials) {
                $response->withHeader('Access-Control-Allow-Credentials', 'true');
            }

            return $response->withHeader('Content-Length', '0');
        }

        // Handle actual requests
        $response->withHeader('Access-Control-Allow-Origin', $this->getAllowedOrigin($origin, $allowedOrigins));
        $response->withHeader('Access-Control-Allow-Methods', implode(', ', $allowedMethods));
        $response->withHeader('Access-Control-Allow-Headers', implode(', ', $allowedHeaders));
        
        if (!empty($exposedHeaders)) {
            $response->withHeader('Access-Control-Expose-Headers', implode(', ', $exposedHeaders));
        }
        
        if ($supportsCredentials) {
            $response->withHeader('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }

    private function getAllowedOrigin(string $origin, array $allowedOrigins): string
    {
        if (in_array('*', $allowedOrigins)) {
            return '*';
        }

        if (in_array($origin, $allowedOrigins)) {
            return $origin;
        }

        return $allowedOrigins[0] ?? '*';
    }
}
