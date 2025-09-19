<?php

declare(strict_types=1);

namespace SharedBackend\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Advanced HTTP response handler with JSON, redirect, and download capabilities
 */
class Response
{
    private ResponseInterface $response;
    private StreamFactoryInterface $streamFactory;
    private array $headers = [];
    private mixed $data = null;

    public function __construct(ResponseInterface $response, StreamFactoryInterface $streamFactory)
    {
        $this->response = $response;
        $this->streamFactory = $streamFactory;
    }

    public function json(mixed $data, int $status = 200): self
    {
        $this->data = $data;
        $this->response = $this->response->withStatus($status);
        $this->response = $this->response->withHeader('Content-Type', 'application/json');
        
        return $this;
    }

    public function success(mixed $data = null, string $message = 'Success', int $status = 200): self
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];

        return $this->json($response, $status);
    }

    public function error(string $message = 'Error', int $status = 400, mixed $data = null): self
    {
        $response = [
            'success' => false,
            'message' => $message,
            'data' => $data
        ];

        return $this->json($response, $status);
    }

    public function validationError(array $errors): self
    {
        return $this->error('Validation failed', 422, ['errors' => $errors]);
    }

    public function notFound(string $message = 'Not Found'): self
    {
        return $this->error($message, 404);
    }

    public function unauthorized(string $message = 'Unauthorized'): self
    {
        return $this->error($message, 401);
    }

    public function forbidden(string $message = 'Forbidden'): self
    {
        return $this->error($message, 403);
    }

    public function serverError(string $message = 'Internal Server Error'): self
    {
        return $this->error($message, 500);
    }

    public function html(string $html, int $status = 200): self
    {
        $this->data = $html;
        $this->response = $this->response->withStatus($status);
        $this->response = $this->response->withHeader('Content-Type', 'text/html; charset=utf-8');
        
        return $this;
    }

    public function text(string $text, int $status = 200): self
    {
        $this->data = $text;
        $this->response = $this->response->withStatus($status);
        $this->response = $this->response->withHeader('Content-Type', 'text/plain; charset=utf-8');
        
        return $this;
    }

    public function xml(string $xml, int $status = 200): self
    {
        $this->data = $xml;
        $this->response = $this->response->withStatus($status);
        $this->response = $this->response->withHeader('Content-Type', 'application/xml; charset=utf-8');
        
        return $this;
    }

    public function redirect(string $url, int $status = 302): self
    {
        $this->response = $this->response->withStatus($status);
        $this->response = $this->response->withHeader('Location', $url);
        
        return $this;
    }

    public function download(string $filePath, string $filename = null): self
    {
        if (!file_exists($filePath)) {
            return $this->notFound('File not found');
        }

        $filename = $filename ?? basename($filePath);
        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';

        $this->response = $this->response->withHeader('Content-Type', $mimeType);
        $this->response = $this->response->withHeader('Content-Disposition', "attachment; filename=\"{$filename}\"");
        $this->response = $this->response->withHeader('Content-Length', (string)filesize($filePath));

        $this->data = file_get_contents($filePath);

        return $this;
    }

    public function stream(StreamInterface $stream, string $filename = null): self
    {
        if ($filename) {
            $this->response = $this->response->withHeader('Content-Disposition', "attachment; filename=\"{$filename}\"");
        }

        $this->response = $this->response->withBody($stream);

        return $this;
    }

    public function withHeader(string $name, string $value): self
    {
        $this->response = $this->response->withHeader($name, $value);
        $this->headers[$name] = $value;
        
        return $this;
    }

    public function withHeaders(array $headers): self
    {
        foreach ($headers as $name => $value) {
            $this->withHeader($name, $value);
        }
        
        return $this;
    }

    public function cors(array $allowedOrigins = ['*'], array $allowedMethods = ['GET', 'POST'], array $allowedHeaders = ['*']): self
    {
        $this->withHeader('Access-Control-Allow-Origin', implode(', ', $allowedOrigins));
        $this->withHeader('Access-Control-Allow-Methods', implode(', ', $allowedMethods));
        $this->withHeader('Access-Control-Allow-Headers', implode(', ', $allowedHeaders));
        $this->withHeader('Access-Control-Max-Age', '86400');
        
        return $this;
    }

    public function cache(int $seconds = 3600): self
    {
        $this->withHeader('Cache-Control', "public, max-age={$seconds}");
        $this->withHeader('Expires', gmdate('D, d M Y H:i:s', time() + $seconds) . ' GMT');
        
        return $this;
    }

    public function noCache(): self
    {
        $this->withHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        $this->withHeader('Pragma', 'no-cache');
        $this->withHeader('Expires', '0');
        
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }

    public function getBody(): StreamInterface
    {
        if ($this->data !== null) {
            $content = is_string($this->data) ? $this->data : json_encode($this->data, JSON_UNESCAPED_UNICODE);
            $this->response = $this->response->withBody($this->streamFactory->createStream($content));
        }

        return $this->response->getBody();
    }

    public function getOriginalResponse(): ResponseInterface
    {
        return $this->response;
    }

    public function send(): void
    {
        // Send status code
        http_response_code($this->getStatusCode());

        // Send headers
        foreach ($this->response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header("{$name}: {$value}");
            }
        }

        // Send body
        echo $this->getBody()->getContents();
    }
}
