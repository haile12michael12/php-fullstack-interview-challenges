<?php

namespace App\Decorator;

use App\Component\HttpRequest;
use App\Component\HttpResponse;

class CompressionMiddleware extends MiddlewareDecorator
{
    private string $compressionType;
    
    public function __construct(
        RequestHandlerInterface $nextHandler,
        string $compressionType = 'gzip'
    ) {
        parent::__construct($nextHandler);
        $this->compressionType = $compressionType;
    }
    
    /**
     * Handle an HTTP request and return a response
     *
     * @param HttpRequest $request The HTTP request to handle
     * @return HttpResponse The HTTP response
     */
    public function handle(HttpRequest $request): HttpResponse
    {
        // Check if client supports compression
        $acceptEncoding = $request->getHeader('Accept-Encoding', '');
        
        if (!$this->supportsCompression($acceptEncoding)) {
            // Client doesn't support compression, pass through
            return $this->nextHandler->handle($request);
        }
        
        // Process the request
        $response = $this->nextHandler->handle($request);
        
        // Compress the response body if it's large enough
        $body = $response->getBody();
        if (strlen($body) > 1024) { // Only compress if body is larger than 1KB
            $compressedBody = $this->compress($body);
            if ($compressedBody !== false) {
                return $response
                    ->withBody($compressedBody)
                    ->withHeader('Content-Encoding', $this->compressionType)
                    ->withHeader('Vary', 'Accept-Encoding');
            }
        }
        
        return $response;
    }
    
    private function supportsCompression(string $acceptEncoding): bool
    {
        return strpos($acceptEncoding, $this->compressionType) !== false;
    }
    
    private function compress(string $data)
    {
        switch ($this->compressionType) {
            case 'gzip':
                return gzencode($data);
            case 'deflate':
                return gzdeflate($data);
            default:
                return false;
        }
    }
}