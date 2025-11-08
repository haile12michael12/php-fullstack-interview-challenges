<?php

namespace App\Exception;

use Exception;
use Throwable;

class ApplicationException extends Exception
{
    protected $context;
    protected $correlationId;
    protected $severity;

    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null,
        array $context = [],
        string $correlationId = null,
        string $severity = 'error'
    ) {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
        $this->correlationId = $correlationId ?? uniqid();
        $this->severity = $severity;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getCorrelationId(): string
    {
        return $this->correlationId;
    }

    public function getSeverity(): string
    {
        return $this->severity;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'context' => $this->context,
            'correlation_id' => $this->correlationId,
            'severity' => $this->severity,
            'trace' => $this->getTraceAsString()
        ];
    }
}