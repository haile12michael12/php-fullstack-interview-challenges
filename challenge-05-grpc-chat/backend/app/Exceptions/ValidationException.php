<?php

namespace App\Exceptions;

class ValidationException extends AppException
{
    private array $errors;

    public function __construct(array $errors = [], string $message = "Validation failed", string $userMessage = "Please check your input and try again", int $code = 422, \Throwable $previous = null)
    {
        parent::__construct($message, $userMessage, $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'errors' => $this->errors
        ]);
    }
}