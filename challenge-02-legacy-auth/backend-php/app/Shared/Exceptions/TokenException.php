<?php

namespace Challenge02\Shared\Exceptions;

class TokenException extends \Exception
{
    public function __construct(string $message = 'Token error', int $code = 401, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}