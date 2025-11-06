<?php

namespace App\Exceptions;

class NotFoundException extends AppException
{
    public function __construct(string $message = "Resource not found", string $userMessage = "The requested resource was not found", int $code = 404, \Throwable $previous = null)
    {
        parent::__construct($message, $userMessage, $code, $previous);
    }
}