<?php

namespace App\Exception;

class ValidationException extends ApplicationException
{
    protected $errors;

    public function __construct(
        string $message = "Validation failed",
        array $errors = [],
        array $context = [],
        string $correlationId = null
    ) {
        parent::__construct($message, 422, null, $context, $correlationId, 'warning');
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $data['errors'] = $this->errors;
        return $data;
    }
}