<?php

declare(strict_types=1);

namespace SharedBackend\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Advanced HTTP request handler with validation and sanitization
 */
class Request
{
    private ServerRequestInterface $request;
    private array $validated = [];
    private array $sanitized = [];

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function getMethod(): string
    {
        return strtoupper($this->request->getMethod());
    }

    public function getUri(): UriInterface
    {
        return $this->request->getUri();
    }

    public function getPath(): string
    {
        return $this->request->getUri()->getPath();
    }

    public function getQueryParams(): array
    {
        return $this->request->getQueryParams();
    }

    public function getQuery(string $key, mixed $default = null): mixed
    {
        return $this->getQueryParams()[$key] ?? $default;
    }

    public function getParsedBody(): array|object|null
    {
        return $this->request->getParsedBody();
    }

    public function getInput(string $key, mixed $default = null): mixed
    {
        $body = $this->getParsedBody();
        if (is_array($body)) {
            return $body[$key] ?? $default;
        }
        if (is_object($body) && property_exists($body, $key)) {
            return $body->$key;
        }
        return $default;
    }

    public function getHeaders(): array
    {
        return $this->request->getHeaders();
    }

    public function getHeader(string $name): array
    {
        return $this->request->getHeader($name);
    }

    public function getHeaderLine(string $name): string
    {
        return $this->request->getHeaderLine($name);
    }

    public function hasHeader(string $name): bool
    {
        return $this->request->hasHeader($name);
    }

    public function getBody(): StreamInterface
    {
        return $this->request->getBody();
    }

    public function getUploadedFiles(): array
    {
        return $this->request->getUploadedFiles();
    }

    public function getUploadedFile(string $key): ?UploadedFileInterface
    {
        $files = $this->getUploadedFiles();
        return $files[$key] ?? null;
    }

    public function getCookieParams(): array
    {
        return $this->request->getCookieParams();
    }

    public function getCookie(string $name, mixed $default = null): mixed
    {
        return $this->getCookieParams()[$name] ?? $default;
    }

    public function getServerParams(): array
    {
        return $this->request->getServerParams();
    }

    public function getServerParam(string $key, mixed $default = null): mixed
    {
        return $this->getServerParams()[$key] ?? $default;
    }

    public function getClientIp(): string
    {
        $headers = [
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'HTTP_CLIENT_IP',
            'REMOTE_ADDR'
        ];

        foreach ($headers as $header) {
            $ip = $this->getServerParam($header);
            if ($ip && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
        }

        return $this->getServerParam('REMOTE_ADDR', '127.0.0.1');
    }

    public function getUserAgent(): string
    {
        return $this->getHeaderLine('User-Agent') ?: 'Unknown';
    }

    public function isAjax(): bool
    {
        return strtolower($this->getHeaderLine('X-Requested-With')) === 'xmlhttprequest';
    }

    public function isJson(): bool
    {
        return str_contains($this->getHeaderLine('Content-Type'), 'application/json');
    }

    public function isPost(): bool
    {
        return $this->getMethod() === 'POST';
    }

    public function isGet(): bool
    {
        return $this->getMethod() === 'GET';
    }

    public function isPut(): bool
    {
        return $this->getMethod() === 'PUT';
    }

    public function isDelete(): bool
    {
        return $this->getMethod() === 'DELETE';
    }

    public function isPatch(): bool
    {
        return $this->getMethod() === 'PATCH';
    }

    public function isOptions(): bool
    {
        return $this->getMethod() === 'OPTIONS';
    }

    public function wantsJson(): bool
    {
        $accept = $this->getHeaderLine('Accept');
        return str_contains($accept, 'application/json') || str_contains($accept, 'text/json');
    }

    public function validate(array $rules): array
    {
        $data = array_merge(
            $this->getQueryParams(),
            is_array($this->getParsedBody()) ? $this->getParsedBody() : []
        );

        $validator = new Validator();
        $this->validated = $validator->validate($data, $rules);
        
        return $this->validated;
    }

    public function getValidated(string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->validated;
        }
        
        return $this->validated[$key] ?? $default;
    }

    public function sanitize(array $data): array
    {
        $sanitizer = new Sanitizer();
        $this->sanitized = $sanitizer->sanitize($data);
        
        return $this->sanitized;
    }

    public function getSanitized(string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->sanitized;
        }
        
        return $this->sanitized[$key] ?? $default;
    }

    public function getOriginalRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
