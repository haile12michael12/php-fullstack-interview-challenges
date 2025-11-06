<?php

namespace Challenge03\Exception;

class ConnectionException extends \Exception
{
    public function __construct(string $message = "Connection error", int $code = 500, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}