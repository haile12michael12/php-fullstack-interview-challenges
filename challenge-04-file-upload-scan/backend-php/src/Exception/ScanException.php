<?php

namespace Challenge04\Exception;

class ScanException extends \Exception
{
    public function __construct(string $message = "File scan error", int $code = 500, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}