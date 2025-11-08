<?php

namespace App\Middleware;

use App\Logger\LoggerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class RequestLoggingMiddleware implements MiddlewareInterface
{
    private LoggerInterface $logger;

    public function __construct()
    {
        $this->logger = LoggerFactory::create('request');
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $startTime = microtime(true);
        $correlationId = $request->getAttribute('correlationId', uniqid());

        // Log request
        $this->logger->info('Request started', [
            'method' => $request->getMethod(),
            'uri' => (string) $request->getUri(),
            'correlation_id' => $correlationId,
            'ip' => $request->getServerParams()['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $request->getHeaderLine('User-Agent'),
            'headers' => $this->filterHeaders($request->getHeaders())
        ]);

        try {
            $response = $handler->handle($request);
            
            $duration = (microtime(true) - $startTime) * 1000; // in milliseconds
            
            // Log response
            $this->logger->info('Request completed', [
                'method' => $request->getMethod(),
                'uri' => (string) $request->getUri(),
                'correlation_id' => $correlationId,
                'status_code' => $response->getStatusCode(),
                'duration_ms' => round($duration, 2),
                'memory_peak' => $this->formatBytes(memory_get_peak_usage(true))
            ]);
            
            return $response;
        } catch (\Throwable $exception) {
            $duration = (microtime(true) - $startTime) * 1000; // in milliseconds
            
            // Log error
            $this->logger->error('Request failed', [
                'method' => $request->getMethod(),
                'uri' => (string) $request->getUri(),
                'correlation_id' => $correlationId,
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
                'duration_ms' => round($duration, 2)
            ]);
            
            throw $exception;
        }
    }

    private function filterHeaders(array $headers): array
    {
        // Remove sensitive headers
        $sensitiveHeaders = ['authorization', 'cookie', 'x-api-key'];
        $filteredHeaders = [];
        
        foreach ($headers as $name => $values) {
            if (!in_array(strtolower($name), $sensitiveHeaders)) {
                $filteredHeaders[$name] = $values;
            }
        }
        
        return $filteredHeaders;
    }

    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}