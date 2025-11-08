<?php

namespace App\Handler;

use App\Exception\ApplicationException;
use App\Exception\ValidationException;
use App\Exception\DatabaseException;
use App\Exception\AuthenticationException;
use App\Exception\AuthorizationException;
use App\Exception\ExternalServiceException;
use App\Logger\LoggerFactory;
use App\Utils\ResponseHelper;
use Exception;
use Psr\Log\LoggerInterface;
use Throwable;

class ExceptionHandler
{
    private LoggerInterface $logger;

    public function __construct()
    {
        $this->logger = LoggerFactory::create('exception');
    }

    public function handle(Throwable $exception)
    {
        // Log the exception
        $this->logException($exception);

        // Handle different types of exceptions
        if ($exception instanceof ApplicationException) {
            return $this->handleApplicationException($exception);
        }

        // Handle generic PHP exceptions
        return $this->handleGenericException($exception);
    }

    private function handleApplicationException(ApplicationException $exception)
    {
        $data = $exception->toArray();
        $httpCode = $this->getHttpCodeFromException($exception);

        // Remove sensitive information from response
        unset($data['trace']);
        unset($data['file']);
        unset($data['line']);

        return ResponseHelper::json($data, $httpCode);
    }

    private function handleGenericException(Throwable $exception)
    {
        $correlationId = uniqid();
        
        // Log the full exception with trace
        $this->logger->error('Unhandled exception', [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'correlation_id' => $correlationId
        ]);

        // Return generic error response
        return ResponseHelper::json([
            'message' => 'An unexpected error occurred',
            'correlation_id' => $correlationId
        ], 500);
    }

    private function logException(Throwable $exception)
    {
        $context = [
            'correlation_id' => null,
            'severity' => 'error'
        ];

        if ($exception instanceof ApplicationException) {
            $context['correlation_id'] = $exception->getCorrelationId();
            $context['severity'] = $exception->getSeverity();
            $context['context'] = $exception->getContext();
        }

        $logMethod = $context['severity'] === 'error' ? 'error' : 'warning';
        $this->logger->$logMethod($exception->getMessage(), $context);
    }

    private function getHttpCodeFromException(ApplicationException $exception): int
    {
        // Map exception codes to HTTP status codes
        $codeMap = [
            AuthenticationException::class => 401,
            AuthorizationException::class => 403,
            ValidationException::class => 422,
            DatabaseException::class => 500,
            ExternalServiceException::class => 502
        ];

        foreach ($codeMap as $exceptionClass => $httpCode) {
            if ($exception instanceof $exceptionClass) {
                return $httpCode;
            }
        }

        // Default to exception code or 500
        return $exception->getCode() ?: 500;
    }
}