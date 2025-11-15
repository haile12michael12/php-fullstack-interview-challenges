<?php

namespace App\Decorator;

use App\Component\HttpRequest;
use App\Component\HttpResponse;

class LoggingMiddleware extends MiddlewareDecorator
{
    /**
     * Handle an HTTP request and return a response
     *
     * @param HttpRequest $request The HTTP request to handle
     * @return HttpResponse The HTTP response
     */
    public function handle(HttpRequest $request): HttpResponse
    {
        // Log the incoming request
        $startTime = microtime(true);
        $startTimeFormatted = date('c');
        
        error_log(sprintf(
            "[%s] REQUEST: %s %s\n",
            $startTimeFormatted,
            $request->getMethod(),
            $request->getUri()
        ));
        
        // Process the request
        $response = $this->nextHandler->handle($request);
        
        // Log the response
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000; // in milliseconds
        $endTimeFormatted = date('c');
        
        error_log(sprintf(
            "[%s] RESPONSE: %d %s (Duration: %.2f ms)\n",
            $endTimeFormatted,
            $response->getStatusCode(),
            $request->getUri(),
            $duration
        ));
        
        return $response;
    }
}