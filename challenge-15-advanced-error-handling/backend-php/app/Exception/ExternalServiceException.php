<?php

namespace App\Exception;

use Throwable;

class ExternalServiceException extends ApplicationException
{
    protected $service;
    protected $endpoint;
    protected $responseBody;

    public function __construct(
        string $message = "External service error",
        int $code = 502,
        ?Throwable $previous = null,
        string $service = null,
        string $endpoint = null,
        string $responseBody = null,
        array $context = [],
        string $correlationId = null
    ) {
        parent::__construct($message, $code, $previous, $context, $correlationId, 'error');
        $this->service = $service;
        $this->endpoint = $endpoint;
        $this->responseBody = $responseBody;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $data['service'] = $this->service;
        $data['endpoint'] = $this->endpoint;
        // Don't include response body in array for security reasons
        return $data;
    }
}