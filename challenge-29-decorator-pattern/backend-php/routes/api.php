<?php

use App\Controller\MiddlewareController;
use App\Controller\RequestController;
use App\Component\HttpRequest;
use App\Component\HttpResponse;

// Create controller instances
$middlewareController = new MiddlewareController();
$requestController = new RequestController();

// Route definitions
$routes = [
    // Middleware routes
    'GET /api/middleware/list' => function(HttpRequest $request) use ($middlewareController) {
        return $middlewareController->listMiddleware($request);
    },
    
    'GET /api/pipeline/config' => function(HttpRequest $request) use ($middlewareController) {
        return $middlewareController->getPipelineConfig($request);
    },
    
    'GET /api/metrics/middleware' => function(HttpRequest $request) use ($middlewareController) {
        return $middlewareController->getMiddlewareMetrics($request);
    },
    
    // Request processing routes
    'POST /api/request/process' => function(HttpRequest $request) use ($requestController) {
        return $requestController->processRequest($request);
    },
    
    'POST /api/middleware/apply' => function(HttpRequest $request) use ($requestController) {
        return $requestController->applyMiddleware($request);
    },
    
    // Default route
    'GET /' => function(HttpRequest $request) {
        return new HttpResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'message' => 'Decorator Pattern Middleware API',
                'version' => '1.0',
                'endpoints' => [
                    'GET /api/middleware/list' => 'List available middleware',
                    'POST /api/middleware/apply' => 'Apply middleware to request',
                    'GET /api/pipeline/config' => 'Get pipeline configuration',
                    'POST /api/request/process' => 'Process request through middleware pipeline',
                    'GET /api/metrics/middleware' => 'Get middleware performance metrics'
                ]
            ])
        );
    }
];

return $routes;