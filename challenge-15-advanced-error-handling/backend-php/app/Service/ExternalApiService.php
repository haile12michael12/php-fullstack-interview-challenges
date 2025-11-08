<?php

namespace App\Service;

use App\Exception\ExternalServiceException;
use App\Recovery\CircuitBreaker;
use App\Recovery\RetryMechanism;
use App\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

class ExternalApiService
{
    private LoggerInterface $logger;
    private RetryMechanism $retryMechanism;
    private CircuitBreaker $circuitBreaker;

    public function __construct()
    {
        $this->logger = LoggerFactory::create('external_api');
        $this->retryMechanism = new RetryMechanism(3, 1000, 2.0);
        $this->circuitBreaker = new CircuitBreaker('external_api', 5, 60);
    }

    /**
     * Fetch data from external API with retry and circuit breaker
     *
     * @param string $endpoint The API endpoint
     * @param array $params Query parameters
     * @return array
     * @throws ExternalServiceException
     */
    public function fetchData(string $endpoint, array $params = []): array
    {
        $url = $this->buildUrl($endpoint, $params);
        
        return $this->circuitBreaker->execute(function() use ($url, $endpoint) {
            return $this->retryMechanism->execute(function() use ($url, $endpoint) {
                $this->logger->info('Fetching data from external API', [
                    'url' => $url,
                    'endpoint' => $endpoint
                ]);

                // Simulate API call
                $response = $this->makeApiCall($url);
                
                if ($response === false) {
                    throw new ExternalServiceException(
                        "Failed to fetch data from external API",
                        502,
                        null,
                        'external_api',
                        $endpoint,
                        null,
                        ['url' => $url]
                    );
                }

                $data = json_decode($response, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new ExternalServiceException(
                        "Invalid JSON response from external API",
                        502,
                        null,
                        'external_api',
                        $endpoint,
                        $response,
                        ['url' => $url]
                    );
                }

                return $data;
            }, [ExternalServiceException::class]);
        });
    }

    /**
     * Simulate making an API call
     *
     * @param string $url
     * @return string|false
     */
    private function makeApiCall(string $url)
    {
        // Simulate random failures for demonstration
        if (rand(1, 10) <= 2) { // 20% failure rate
            return false;
        }

        // Simulate response
        return json_encode([
            'status' => 'success',
            'data' => [
                'id' => rand(1, 1000),
                'name' => 'Sample Data',
                'timestamp' => date('c')
            ]
        ]);
    }

    /**
     * Build URL with query parameters
     *
     * @param string $endpoint
     * @param array $params
     * @return string
     */
    private function buildUrl(string $endpoint, array $params): string
    {
        $baseUrl = getenv('EXTERNAL_API_BASE_URL') ?: 'https://api.example.com';
        $url = rtrim($baseUrl, '/') . '/' . ltrim($endpoint, '/');
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $url;
    }

    /**
     * Get circuit breaker status
     *
     * @return array
     */
    public function getCircuitBreakerStatus(): array
    {
        return [
            'state' => $this->circuitBreaker->getState(),
            'failure_count' => $this->circuitBreaker->getFailureCount(),
            'last_failure' => $this->circuitBreaker->getLastFailureTime()?->format('c')
        ];
    }
}