<?php

namespace App\Domain\Markdown\Exceptions;

use Exception;

class InvalidMarkdownException extends Exception
{
    public function __construct(string $message = "Invalid markdown content", int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}