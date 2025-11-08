<?php

namespace App\Controller;

use App\Exception\ValidationException;
use App\Exception\DatabaseException;
use App\Exception\AuthenticationException;
use App\Exception\AuthorizationException;
use App\Exception\ExternalServiceException;
use App\Service\ExternalApiService;
use App\Service\DatabaseService;
use App\Utils\ResponseHelper;
use App\Recovery\FallbackStrategy;
use PDOException;

class ErrorTestController
{
    private ExternalApiService $externalApiService;
    private DatabaseService $databaseService;

    public function __construct()
    {
        $this->externalApiService = new ExternalApiService();
        $this->databaseService = new DatabaseService();
    }

    /**
     * Test validation exception
     *
     * @return array
     * @throws ValidationException
     */
    public function testValidation(): array
    {
        throw new ValidationException(
            "Invalid input data",
            [
                'email' => 'Email is required',
                'password' => 'Password must be at least 8 characters'
            ],
            ['action' => 'user_registration']
        );
    }

    /**
     * Test database exception
     *
     * @return array
     * @throws DatabaseException
     */
    public function testDatabase(): array
    {
        try {
            // This will fail because the table doesn't exist
            $this->databaseService->query("SELECT * FROM non_existent_table WHERE id = ?", [1]);
        } catch (PDOException $e) {
            throw new DatabaseException(
                "Database query failed",
                500,
                $e,
                "SELECT * FROM non_existent_table WHERE id = ?",
                [1]
            );
        }
        
        return ResponseHelper::json(['message' => 'Database test completed']);
    }

    /**
     * Test authentication exception
     *
     * @return array
     * @throws AuthenticationException
     */
    public function testAuthentication(): array
    {
        throw new AuthenticationException(
            "Invalid credentials provided",
            401,
            ['username' => 'testuser']
        );
    }

    /**
     * Test authorization exception
     *
     * @return array
     * @throws AuthorizationException
     */
    public function testAuthorization(): array
    {
        throw new AuthorizationException(
            "Insufficient permissions to access this resource",
            403,
            ['required_permissions' => ['admin', 'moderator']],
            ['resource' => 'admin_panel']
        );
    }

    /**
     * Test external service exception
     *
     * @return array
     * @throws ExternalServiceException
     */
    public function testExternalService(): array
    {
        // This will fail due to circuit breaker or retry mechanism
        return $this->externalApiService->fetchData('/users/1');
    }

    /**
     * Test external service with fallback
     *
     * @return array
     */
    public function testExternalServiceWithFallback(): array
    {
        $fallback = new FallbackStrategy();
        $fallback->addStrategy(ExternalServiceException::class, function() {
            return [
                'status' => 'fallback',
                'data' => [
                    'id' => 1,
                    'name' => 'Cached User Data',
                    'email' => 'user@example.com'
                ]
            ];
        });
        
        $fallback->addDefaultStrategy(function() {
            return ['status' => 'error', 'message' => 'Service unavailable'];
        });

        return $fallback->execute(function() {
            return $this->externalApiService->fetchData('/users/1');
        });
    }

    /**
     * Get circuit breaker status
     *
     * @return array
     */
    public function getCircuitBreakerStatus(): array
    {
        return $this->externalApiService->getCircuitBreakerStatus();
    }

    /**
     * Test generic exception
     *
     * @return array
     * @throws \Exception
     */
    public function testGeneric(): array
    {
        throw new \Exception("This is a generic exception for testing purposes");
    }
}