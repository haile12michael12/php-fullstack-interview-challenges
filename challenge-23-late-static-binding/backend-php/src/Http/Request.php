<?php

namespace App\Http;

class Request
{
    private string $method;
    private string $path;
    private array $queryParams;
    private array $parsedBody;
    private array $headers;
    private array $serverParams;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $this->queryParams = $_GET;
        $this->parsedBody = $_POST;
        $this->headers = $this->getAllHeaders();
        $this->serverParams = $_SERVER;
    }

    public static function createFromGlobals(): self
    {
        return new self();
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getParsedBody()
    {
        return $this->parsedBody;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeaderLine(string $name): string
    {
        $headerName = str_replace('-', '_', strtoupper($name));
        return $_SERVER["HTTP_{$headerName}"] ?? '';
    }

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    private function getAllHeaders(): array
    {
        $headers = [];
        
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        } else {
            // Fallback for environments where getallheaders is not available
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) === 'HTTP_') {
                    $headerName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                    $headers[$headerName] = $value;
                }
            }
        }
        
        return $headers;
    }
}