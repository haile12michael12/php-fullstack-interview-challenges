<?php

namespace App\Decorator;

use App\Component\HttpRequest;
use App\Component\HttpResponse;

class RateLimitMiddleware extends MiddlewareDecorator
{
    private array $requestCounts = [];
    private int $maxRequests;
    private int $timeWindow; // in seconds
    
    public function __construct(
        RequestHandlerInterface $nextHandler,
        int $maxRequests = 10,
        int $timeWindow = 60
    ) {
        parent::__construct($nextHandler);
        $this->maxRequests = $maxRequests;
        $this->timeWindow = $timeWindow;
    }
    
    /**
     * Handle an HTTP request and return a response
     *
     * @param HttpRequest $request The HTTP request to handle
     * @return HttpResponse The HTTP response
     */
    public function handle(HttpRequest $request): HttpResponse
    {
        // Get client IP (simplified - in a real app you might use more sophisticated methods)
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        
        // Clean up old requests outside the time window
        $currentTime = time();
        if (!isset($this->requestCounts[$clientIp])) {
            $this->requestCounts[$clientIp] = [];
        }
        
        // Remove requests outside the time window
        $this->requestCounts[$clientIp] = array_filter(
            $this->requestCounts[$clientIp],
            fn($timestamp) => ($currentTime - $timestamp) < $this->timeWindow
        );
        
        // Check if limit exceeded
        if (count($this->requestCounts[$clientIp]) >= $this->maxRequests) {
            return new HttpResponse(
                429,
                ['Content-Type' => 'application/json'],
                json_encode([
                    'error' => 'Too Many Requests',
                    'message' => 'Rate limit exceeded. Please try again later.'
                ])
            );
        }
        
        // Record this request
        $this->requestCounts[$clientIp][] = $currentTime;
        
        // Pass the request to the next handler
        return $this->nextHandler->handle($request);
    }
}