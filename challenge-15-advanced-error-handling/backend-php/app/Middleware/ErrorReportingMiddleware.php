<?php

namespace App\Middleware;

use App\Logger\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class ErrorReportingMiddleware implements MiddlewareInterface
{
    private LoggerInterface $logger;

    public function __construct()
    {
        $this->logger = LoggerFactory::create('error_reporting');
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        // Store original error reporting level
        $originalLevel = error_reporting();
        
        // Set error reporting based on environment
        if (getenv('APP_ENV') === 'production') {
            error_reporting(E_ERROR | E_WARNING | E_PARSE);
        } else {
            error_reporting(E_ALL);
        }

        try {
            $response = $handler->handle($request);
            return $response;
        } catch (\Throwable $exception) {
            // Log the exception with context
            $this->logger->error('Unhandled exception in middleware', [
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString(),
                'correlation_id' => $request->getAttribute('correlationId')
            ]);
            
            // Re-throw the exception to be handled by global error handler
            throw $exception;
        } finally {
            // Restore original error reporting level
            error_reporting($originalLevel);
        }
    }
}