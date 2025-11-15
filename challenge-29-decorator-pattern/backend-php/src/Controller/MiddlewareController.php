<?php

namespace App\Controller;

use App\Component\HttpRequest;
use App\Component\HttpResponse;
use App\Service\MiddlewareService;

class MiddlewareController
{
    private MiddlewareService $middlewareService;
    
    public function __construct()
    {
        $this->middlewareService = new MiddlewareService();
    }
    
    /**
     * List available middleware
     *
     * @param HttpRequest $request
     * @return HttpResponse
     */
    public function listMiddleware(HttpRequest $request): HttpResponse
    {
        $middlewareList = $this->middlewareService->getAvailableMiddleware();
        
        return (new HttpResponse())
            ->json([
                'success' => true,
                'middleware' => array_map(function($middleware, $key) {
                    return [
                        'id' => $key,
                        'name' => $middleware['name'],
                        'description' => $middleware['description']
                    ];
                }, $middlewareList, array_keys($middlewareList))
            ]);
    }
    
    /**
     * Get pipeline configuration
     *
     * @param HttpRequest $request
     * @return HttpResponse
     */
    public function getPipelineConfig(HttpRequest $request): HttpResponse
    {
        $config = $this->middlewareService->getConfiguration();
        
        return (new HttpResponse())
            ->json([
                'success' => true,
                'config' => $config
            ]);
    }
    
    /**
     * Get middleware metrics
     *
     * @param HttpRequest $request
     * @return HttpResponse
     */
    public function getMiddlewareMetrics(HttpRequest $request): HttpResponse
    {
        // In a real implementation, this would collect actual metrics
        // For now, we'll return sample data
        $metrics = [
            'auth' => [
                'requests' => 1245,
                'success_rate' => 98.2,
                'avg_response_time' => 2.3
            ],
            'logging' => [
                'requests' => 5678,
                'success_rate' => 100.0,
                'avg_response_time' => 0.1
            ],
            'rate_limit' => [
                'requests' => 5678,
                'blocked' => 42,
                'success_rate' => 99.3
            ],
            'cors' => [
                'requests' => 3456,
                'success_rate' => 100.0,
                'avg_response_time' => 0.2
            ],
            'compression' => [
                'requests' => 2345,
                'compressed' => 1876,
                'avg_compression_ratio' => 0.65
            ]
        ];
        
        return (new HttpResponse())
            ->json([
                'success' => true,
                'metrics' => $metrics
            ]);
    }
}