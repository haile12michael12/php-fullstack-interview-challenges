<?php

namespace App\Decorator;

use App\Component\HttpRequest;
use App\Component\HttpResponse;

class CorsMiddleware extends MiddlewareDecorator
{
    private array $allowedOrigins;
    private array $allowedMethods;
    private array $allowedHeaders;
    
    public function __construct(
        RequestHandlerInterface $nextHandler,
        array $allowedOrigins = ['*'],
        array $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        array $allowedHeaders = ['Content-Type', 'Authorization', 'X-Requested-With']
    ) {
        parent::__construct($nextHandler);
        $this->allowedOrigins = $allowedOrigins;
        $this->allowedMethods = $allowedMethods;
        $this->allowedHeaders = $allowedHeaders;
    }
    
    /**
     * Handle an HTTP request and return a response
     *
     * @param HttpRequest $request The HTTP request to handle
     * @return HttpResponse The HTTP response
     */
    public function handle(HttpRequest $request): HttpResponse
    {
        // Handle preflight OPTIONS request
        if ($request->getMethod() === 'OPTIONS') {
            return (new HttpResponse(200))
                ->withHeader('Access-Control-Allow-Origin', $this->getAllowedOrigin($request))
                ->withHeader('Access-Control-Allow-Methods', implode(', ', $this->allowedMethods))
                ->withHeader('Access-Control-Allow-Headers', implode(', ', $this->allowedHeaders))
                ->withHeader('Access-Control-Max-Age', '86400'); // 24 hours
        }
        
        // Process the request
        $response = $this->nextHandler->handle($request);
        
        // Add CORS headers to the response
        return $response
            ->withHeader('Access-Control-Allow-Origin', $this->getAllowedOrigin($request))
            ->withHeader('Access-Control-Allow-Methods', implode(', ', $this->allowedMethods))
            ->withHeader('Access-Control-Allow-Headers', implode(', ', $this->allowedHeaders));
    }
    
    private function getAllowedOrigin(HttpRequest $request): string
    {
        $origin = $request->getHeader('Origin', '');
        
        if (in_array('*', $this->allowedOrigins)) {
            return '*';
        }
        
        if (in_array($origin, $this->allowedOrigins)) {
            return $origin;
        }
        
        return $this->allowedOrigins[0] ?? '*';
    }
}