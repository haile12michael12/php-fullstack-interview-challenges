<?php

namespace Challenge04\Core;

class Response
{
    private int $statusCode;
    private array $headers;
    private string $body;

    public function __construct(int $statusCode = 200, array $headers = [], string $body = '')
    {
        $this->statusCode = $statusCode;
        $this->headers = array_merge(['Content-Type' => 'application/json'], $headers);
        $this->body = $body;
    }

    public static function json(array $data, int $statusCode = 200): self
    {
        return new self($statusCode, ['Content-Type' => 'application/json'], json_encode($data));
    }

    public static function error(string $message, int $statusCode = 400): self
    {
        return new self($statusCode, ['Content-Type' => 'application/json'], json_encode(['error' => $message]));
    }

    public static function success(string $message, array $data = [], int $statusCode = 200): self
    {
        return new self($statusCode, ['Content-Type' => 'application/json'], json_encode(array_merge(['message' => $message], $data)));
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }
        
        echo $this->body;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}