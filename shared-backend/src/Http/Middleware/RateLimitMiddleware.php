<?php

declare(strict_types=1);

namespace SharedBackend\Http\Middleware;

use SharedBackend\Http\Request;
use SharedBackend\Http\Response;
use SharedBackend\Core\Cache;
use SharedBackend\Core\Logger;

/**
 * Rate limiting middleware with sliding window algorithm
 */
class RateLimitMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Cache $cache,
        private Logger $logger,
        private int $maxRequests = 100,
        private int $windowMinutes = 1,
        private string $keyPrefix = 'rate_limit'
    ) {
    }

    public function handle(Request $request, callable $next): Response
    {
        $key = $this->generateKey($request);
        $current = $this->getCurrentRequests($key);

        if ($current >= $this->maxRequests) {
            $this->logger->warning('Rate limit exceeded', [
                'ip' => $request->getClientIp(),
                'user_agent' => $request->getUserAgent(),
                'current_requests' => $current,
                'max_requests' => $this->maxRequests
            ]);

            return new Response(
                new \GuzzleHttp\Psr7\Response(),
                new \GuzzleHttp\Psr7\StreamFactory()
            )->withHeader('X-RateLimit-Limit', (string)$this->maxRequests)
              ->withHeader('X-RateLimit-Remaining', '0')
              ->withHeader('X-RateLimit-Reset', (string)(time() + ($this->windowMinutes * 60)))
              ->error('Rate limit exceeded. Too many requests.', 429);
        }

        $this->incrementRequests($key);

        $response = $next($request);

        $remaining = $this->maxRequests - $current - 1;
        $resetTime = time() + ($this->windowMinutes * 60);

        return $response
            ->withHeader('X-RateLimit-Limit', (string)$this->maxRequests)
            ->withHeader('X-RateLimit-Remaining', (string)max(0, $remaining))
            ->withHeader('X-RateLimit-Reset', (string)$resetTime);
    }

    private function generateKey(Request $request): string
    {
        $ip = $request->getClientIp();
        $userAgent = $request->getUserAgent();
        $endpoint = $request->getPath();
        
        return "{$this->keyPrefix}:{$ip}:{$endpoint}:" . md5($userAgent);
    }

    private function getCurrentRequests(string $key): int
    {
        return (int)$this->cache->get($key, 0);
    }

    private function incrementRequests(string $key): void
    {
        $current = $this->getCurrentRequests($key);
        $ttl = $this->windowMinutes * 60;
        
        $this->cache->set($key, $current + 1, $ttl);
    }
}
