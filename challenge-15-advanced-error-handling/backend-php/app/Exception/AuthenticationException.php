<?php

namespace App\Exception;

class AuthenticationException extends ApplicationException
{
    public function __construct(
        string $message = "Authentication failed",
        int $code = 401,
        array $context = [],
        string $correlationId = null
    ) {
        parent::__construct($message, $code, null, $context, $correlationId, 'warning');
    }
}