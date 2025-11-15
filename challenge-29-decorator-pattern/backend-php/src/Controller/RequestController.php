<?php

namespace App\Controller;

use App\Component\HttpRequest;
use App\Component\HttpResponse;
use App\Service\MiddlewareService;
use App\Service\PipelineBuilder;
use App\Component\BaseRequestHandler;

class RequestController
{
    private MiddlewareService $middlewareService;
    
    public function __construct()
    {
        $this->middlewareService = new MiddlewareService();
    }
    
    /**
     * Process a request through the middleware pipeline
     *
     * @param HttpRequest $request
     * @return HttpResponse
     */
    public function processRequest(HttpRequest $request): HttpResponse
    {
        // Get the requested middleware from the request body
        $body = json_decode($request->getBody(), true);
        $middlewareList = $body['middleware'] ?? [];
        $testRequestData = $body['request'] ?? [];
        
        // Validate middleware list
        $availableMiddleware = $this->middlewareService->getAvailableMiddleware();
        $invalidMiddleware = array_diff($middlewareList, array_keys($availableMiddleware));
        
        if (!empty($invalidMiddleware)) {
            return (new HttpResponse(400))
                ->json([
                    'success' => false,
                    'error' => 'Invalid middleware specified',
                    'invalid_middleware' => array_values($invalidMiddleware)
                ]);
        }
        
        // Create a test request
        $testRequest = new HttpRequest(
            $testRequestData['method'] ?? 'GET',
            $testRequestData['uri'] ?? '/',
            $testRequestData['headers'] ?? [],
            $testRequestData['body'] ?? '',
            $testRequestData['query_params'] ?? []
        );
        
        // Create the final handler
        $finalHandler = new BaseRequestHandler();
        
        // Build the pipeline
        $pipeline = $this->middlewareService->createPipeline($middlewareList, $finalHandler);
        
        // Process the request
        try {
            $response = $pipeline->handle($testRequest);
            
            return (new HttpResponse())
                ->json([
                    'success' => true,
                    'response' => [
                        'status_code' => $response->getStatusCode(),
                        'headers' => $response->getHeaders(),
                        'body' => $response->getBody()
                    ]
                ]);
        } catch (\Exception $e) {
            return (new HttpResponse(500))
                ->json([
                    'success' => false,
                    'error' => 'Error processing request',
                    'message' => $e->getMessage()
                ]);
        }
    }
    
    /**
     * Apply middleware to a request (simplified version)
     *
     * @param HttpRequest $request
     * @return HttpResponse
     */
    public function applyMiddleware(HttpRequest $request): HttpResponse
    {
        // Get the requested middleware from the request body
        $body = json_decode($request->getBody(), true);
        $middlewareList = $body['middleware'] ?? [];
        
        // Validate middleware list
        $availableMiddleware = $this->middlewareService->getAvailableMiddleware();
        $invalidMiddleware = array_diff($middlewareList, array_keys($availableMiddleware));
        
        if (!empty($invalidMiddleware)) {
            return (new HttpResponse(400))
                ->json([
                    'success' => false,
                    'error' => 'Invalid middleware specified',
                    'invalid_middleware' => array_values($invalidMiddleware)
                ]);
        }
        
        // Return the list of applied middleware
        $appliedMiddleware = [];
        foreach ($middlewareList as $middlewareKey) {
            if (isset($availableMiddleware[$middlewareKey])) {
                $appliedMiddleware[] = [
                    'id' => $middlewareKey,
                    'name' => $availableMiddleware[$middlewareKey]['name'],
                    'description' => $availableMiddleware[$middlewareKey]['description']
                ];
            }
        }
        
        return (new HttpResponse())
            ->json([
                'success' => true,
                'applied_middleware' => $appliedMiddleware
            ]);
    }
}