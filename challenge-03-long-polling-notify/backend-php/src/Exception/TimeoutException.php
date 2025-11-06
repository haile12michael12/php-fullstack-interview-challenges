<?php

namespace Challenge03\Exception;

class TimeoutException extends \Exception
{
    public function __construct(string $message = "Request timeout", int $code = 408, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}