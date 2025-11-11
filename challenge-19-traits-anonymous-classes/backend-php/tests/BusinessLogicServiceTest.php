<?php

namespace Tests;

use App\Service\BusinessLogicService;
use PHPUnit\Framework\TestCase;

class BusinessLogicServiceTest extends TestCase
{
    private BusinessLogicService $service;
    
    protected function setUp(): void
    {
        $this->service = new BusinessLogicService();
    }
    
    public function testGetAvailableStrategies(): void
    {
        $strategies = $this->service->getAvailableStrategies();
        
        $this->assertIsArray($strategies);
        $this->assertContains('logger_strategy', $strategies);
    }
    
    public function testExecuteStrategy(): void
    {
        $result = $this->service->executeStrategy('logger', [
            'message' => 'Test message',
            'level' => 'info'
        ]);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('logged', $result['status']);
    }
    
    public function testExecuteInvalidStrategy(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->service->executeStrategy('invalid_strategy', []);
    }
    
    public function testPerformCalculation(): void
    {
        $result = $this->service->performCalculation(3);
        
        // 3^3 = 27
        $this->assertEquals(27, $result);
    }
    
    public function testValidateEntity(): void
    {
        // Test valid data
        $validData = [
            'email' => 'test@example.com',
            'age' => '25'
        ];
        
        $result = $this->service->validateEntity($validData);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('success', $result['status']);
        
        // Test invalid data
        $invalidData = [
            'email' => 'invalid-email',
            'age' => 'twenty-five'
        ];
        
        $result = $this->service->validateEntity($invalidData);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals('error', $result['status']);
    }
    
    public function testCacheOperations(): void
    {
        $key = 'test_cache_key';
        $value = 'test_cache_value';
        
        // Test caching data
        $success = $this->service->cacheData($key, $value, 3600);
        $this->assertTrue($success);
        
        // Test retrieving cached data
        $cachedValue = $this->service->getCachedData($key);
        $this->assertEquals($value, $cachedValue);
    }
    
    public function testCreateUser(): void
    {
        $userData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com'
        ];
        
        $user = $this->service->createUser($userData);
        
        $this->assertIsArray($user);
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('name', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('created_at', $user);
        $this->assertArrayHasKey('updated_at', $user);
    }
    
    public function testGetUserStats(): void
    {
        $stats = $this->service->getUserStats();
        
        $this->assertIsArray($stats);
        $this->assertArrayHasKey('users', $stats);
        $this->assertArrayHasKey('cache_stats', $stats);
        $this->assertArrayHasKey('strategies', $stats);
    }
}