<?php

namespace App\Infrastructure;

class Response
{
    private array $data;
    private int $statusCode;
    private array $headers;
    
    public function __construct(array $data = [], int $statusCode = 200, array $headers = [])
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
        $this->headers = array_merge([
            'Content-Type' => 'application/json'
        ], $headers);
    }
    
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    public function getHeaders(): array
    {
        return $this->headers;
    }
    
    public function getBody(): string
    {
        return json_encode($this->data, JSON_UNESCAPED_UNICODE);
    }
}