<?php

namespace App\Http;

class Response
{
    private array $data;
    private int $statusCode;
    
    public function __construct(array $data = [], int $statusCode = 200)
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
    }
    
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
    
    public function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json'
        ];
    }
    
    public function getBody(): string
    {
        return json_encode($this->data, JSON_UNESCAPED_UNICODE);
    }
}