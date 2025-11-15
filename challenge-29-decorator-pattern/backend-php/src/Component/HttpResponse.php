<?php

namespace App\Component;

class HttpResponse
{
    private int $statusCode;
    private array $headers;
    private string $body;

    public function __construct(
        int $statusCode = 200,
        array $headers = [],
        string $body = ''
    ) {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeader(string $name, $default = null)
    {
        $name = strtolower($name);
        foreach ($this->headers as $key => $value) {
            if (strtolower($key) === $name) {
                return $value;
            }
        }
        return $default;
    }

    public function hasHeader(string $name): bool
    {
        $name = strtolower($name);
        foreach ($this->headers as $key => $value) {
            if (strtolower($key) === $name) {
                return true;
            }
        }
        return false;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function withStatus(int $code): self
    {
        $new = clone $this;
        $new->statusCode = $code;
        return $new;
    }

    public function withHeader(string $name, string $value): self
    {
        $new = clone $this;
        $new->headers[$name] = $value;
        return $new;
    }

    public function withAddedHeader(string $name, string $value): self
    {
        $new = clone $this;
        if (isset($new->headers[$name])) {
            $new->headers[$name] .= ', ' . $value;
        } else {
            $new->headers[$name] = $value;
        }
        return $new;
    }

    public function withBody(string $body): self
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }

    public function json(array $data): self
    {
        return $this
            ->withHeader('Content-Type', 'application/json')
            ->withBody(json_encode($data));
    }
}