<?php

namespace App\Helpers;

class Response
{
    protected $data;
    protected int $statusCode;
    protected array $headers;

    public function __construct($data = null, int $statusCode = 200, array $headers = [])
    {
        $this->data = $data;
        $this->statusCode = $statusCode;
        $this->headers = array_merge([
            'Content-Type' => 'application/json',
        ], $headers);
    }

    public function __call(string $method, array $parameters)
    {
        // Handle status code methods
        if (strpos($method, 'withStatus') === 0) {
            $code = (int)substr($method, 10);
            return $this->setStatusCode($code);
        }
        
        // Handle header methods
        if (strpos($method, 'withHeader') === 0) {
            $header = lcfirst(substr($method, 10));
            return $this->addHeader($header, $parameters[0]);
        }
        
        throw new \BadMethodCallException("Method {$method} does not exist.");
    }

    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function addHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function addHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function json(): self
    {
        $this->headers['Content-Type'] = 'application/json';
        return $this;
    }

    public function xml(): self
    {
        $this->headers['Content-Type'] = 'application/xml';
        return $this;
    }

    public function html(): self
    {
        $this->headers['Content-Type'] = 'text/html';
        return $this;
    }

    public function send(): void
    {
        // Set headers
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        
        // Set status code
        http_response_code($this->statusCode);
        
        // Send data
        if ($this->headers['Content-Type'] === 'application/json') {
            echo json_encode($this->data, JSON_UNESCAPED_UNICODE);
        } else {
            echo (string)$this->data;
        }
        
        exit;
    }

    public function __toString(): string
    {
        if (is_array($this->data)) {
            return json_encode($this->data, JSON_UNESCAPED_UNICODE);
        }
        
        return (string)$this->data;
    }

    public function __get(string $property)
    {
        return $this->$property ?? null;
    }

    public function __set(string $property, $value): void
    {
        $this->$property = $value;
    }

    public function __isset(string $property): bool
    {
        return isset($this->$property);
    }

    public function __unset(string $property): void
    {
        unset($this->$property);
    }

    public function getData()
    {
        return $this->data;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}