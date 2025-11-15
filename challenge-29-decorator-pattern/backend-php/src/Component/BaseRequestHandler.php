<?php

namespace App\Component;

class BaseRequestHandler implements RequestHandlerInterface
{
    /**
     * Handle an HTTP request and return a response
     *
     * @param HttpRequest $request The HTTP request to handle
     * @return HttpResponse The HTTP response
     */
    public function handle(HttpRequest $request): HttpResponse
    {
        // Default implementation returns a simple response
        return new HttpResponse(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'message' => 'Request handled successfully',
                'method' => $request->getMethod(),
                'uri' => $request->getUri(),
                'timestamp' => date('c')
            ])
        );
    }
}