<?php

namespace Challenge04\Exception;

class UploadException extends \Exception
{
    public function __construct(string $message = "File upload error", int $code = 400, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}