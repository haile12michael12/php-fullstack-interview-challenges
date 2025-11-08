<?php

namespace App\Logger;

use App\Logger\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CorrelationIdMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        // Get or generate correlation ID
        $correlationId = $request->getHeaderLine('X-Correlation-ID');
        if (empty($correlationId)) {
            $correlationId = uniqid();
        }

        // Add correlation ID to request attributes
        $request = $request->withAttribute('correlationId', $correlationId);

        // Process the request
        $response = $handler->handle($request);

        // Add correlation ID to response headers
        return $response->withHeader('X-Correlation-ID', $correlationId);
    }
}