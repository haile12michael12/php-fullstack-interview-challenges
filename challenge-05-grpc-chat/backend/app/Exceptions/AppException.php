<?php

namespace App\Exceptions;

class AppException extends \Exception
{
    protected string $userMessage;

    public function __construct(string $message = "", string $userMessage = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->userMessage = $userMessage ?: $message;
    }

    public function getUserMessage(): string
    {
        return $this->userMessage;
    }

    public function toArray(): array
    {
        return [
            'error' => true,
            'message' => $this->getUserMessage(),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine()
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}