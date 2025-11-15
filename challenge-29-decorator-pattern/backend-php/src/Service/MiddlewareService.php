<?php

namespace App\Service;

use App\Component\RequestHandlerInterface;
use App\Component\BaseRequestHandler;
use App\Decorator\AuthMiddleware;
use App\Decorator\LoggingMiddleware;
use App\Decorator\RateLimitMiddleware;
use App\Decorator\CorsMiddleware;
use App\Decorator\CompressionMiddleware;

class MiddlewareService
{
    /**
     * Get a list of available middleware
     *
     * @return array
     */
    public function getAvailableMiddleware(): array
    {
        return [
            'auth' => [
                'name' => 'Authentication',
                'description' => 'Validates API tokens and adds user information to requests',
                'class' => AuthMiddleware::class
            ],
            'logging' => [
                'name' => 'Logging',
                'description' => 'Logs incoming requests and outgoing responses',
                'class' => LoggingMiddleware::class
            ],
            'rate_limit' => [
                'name' => 'Rate Limiting',
                'description' => 'Limits the number of requests per IP address',
                'class' => RateLimitMiddleware::class
            ],
            'cors' => [
                'name' => 'CORS',
                'description' => 'Handles Cross-Origin Resource Sharing headers',
                'class' => CorsMiddleware::class
            ],
            'compression' => [
                'name' => 'Compression',
                'description' => 'Compresses response bodies to reduce bandwidth',
                'class' => CompressionMiddleware::class
            ]
        ];
    }
    
    /**
     * Create a middleware pipeline
     *
     * @param array $middlewareList List of middleware to apply
     * @param RequestHandlerInterface $finalHandler The final request handler
     * @return RequestHandlerInterface
     */
    public function createPipeline(array $middlewareList, RequestHandlerInterface $finalHandler): RequestHandlerInterface
    {
        $handler = $finalHandler;
        $availableMiddleware = $this->getAvailableMiddleware();
        
        // Process middleware in reverse order (last in, first out)
        foreach (array_reverse($middlewareList) as $middlewareKey) {
            if (isset($availableMiddleware[$middlewareKey])) {
                $middlewareClass = $availableMiddleware[$middlewareKey]['class'];
                $handler = new $middlewareClass($handler);
            }
        }
        
        return $handler;
    }
    
    /**
     * Get middleware configuration
     *
     * @return array
     */
    public function getConfiguration(): array
    {
        return [
            'default_middleware' => ['cors', 'logging'],
            'auth_required_endpoints' => ['/api/secure/*'],
            'rate_limit_settings' => [
                'max_requests' => 100,
                'time_window' => 3600 // 1 hour
            ]
        ];
    }
}