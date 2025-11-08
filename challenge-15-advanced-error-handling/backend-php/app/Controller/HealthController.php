<?php

namespace App\Controller;

use App\Service\DatabaseService;
use App\Service\ExternalApiService;
use App\Utils\ResponseHelper;

class HealthController
{
    private DatabaseService $databaseService;
    private ExternalApiService $externalApiService;

    public function __construct()
    {
        $this->databaseService = new DatabaseService();
        $this->externalApiService = new ExternalApiService();
    }

    /**
     * Health check endpoint
     *
     * @return array
     */
    public function health(): array
    {
        $checks = [
            'application' => $this->checkApplication(),
            'database' => $this->checkDatabase(),
            'external_api' => $this->checkExternalApi(),
        ];

        $status = 'healthy';
        $httpCode = 200;

        foreach ($checks as $check) {
            if ($check['status'] === 'unhealthy') {
                $status = 'unhealthy';
                $httpCode = 503;
                break;
            }
        }

        return ResponseHelper::json([
            'status' => $status,
            'timestamp' => date('c'),
            'checks' => $checks
        ], $httpCode);
    }

    /**
     * Application health check
     *
     * @return array
     */
    private function checkApplication(): array
    {
        return [
            'status' => 'healthy',
            'message' => 'Application is running',
            'version' => getenv('APP_VERSION') ?: '1.0.0'
        ];
    }

    /**
     * Database health check
     *
     * @return array
     */
    private function checkDatabase(): array
    {
        try {
            $connection = $this->databaseService->getConnection();
            $connection->query('SELECT 1');
            
            return [
                'status' => 'healthy',
                'message' => 'Database connection successful'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Database connection failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * External API health check
     *
     * @return array
     */
    private function checkExternalApi(): array
    {
        $circuitBreakerStatus = $this->externalApiService->getCircuitBreakerStatus();
        
        if ($circuitBreakerStatus['state'] === 'open') {
            return [
                'status' => 'degraded',
                'message' => 'External API circuit breaker is open',
                'details' => $circuitBreakerStatus
            ];
        }

        try {
            // Test external API connectivity
            $this->externalApiService->fetchData('/health');
            
            return [
                'status' => 'healthy',
                'message' => 'External API is accessible'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'degraded',
                'message' => 'External API is not accessible: ' . $e->getMessage()
            ];
        }
    }
}