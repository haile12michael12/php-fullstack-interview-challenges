<?php

namespace Tests;

use App\Factory\AnonymousClassFactory;
use App\Factory\MockFactory;
use PHPUnit\Framework\TestCase;

class AnonymousClassTest extends TestCase
{
    public function testAnonymousClassFactory(): void
    {
        // Test creating a logger strategy
        $loggerStrategy = AnonymousClassFactory::createLoggerStrategy();
        
        $this->assertInstanceOf(\App\Contracts\StrategyInterface::class, $loggerStrategy);
        $this->assertEquals('logger_strategy', $loggerStrategy->getName());
        
        // Test executing the strategy
        $result = $loggerStrategy->execute([
            'message' => 'Test log message',
            'level' => 'info'
        ]);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('message', $result);
        $this->assertEquals('logged', $result['status']);
        
        // Test creating a cacheable calculator
        $calculator = AnonymousClassFactory::createCacheableCalculator();
        
        // Test that it has the CacheableTrait methods
        $this->assertTrue(method_exists($calculator, 'getFromCache'));
        
        // Test creating a validatable entity
        $entity = AnonymousClassFactory::createValidatableEntity();
        
        // Test that it has the ValidatableTrait methods
        $this->assertTrue(method_exists($entity, 'validate'));
    }
    
    public function testMockFactory(): void
    {
        // Test creating an array cache
        $cache = MockFactory::createArrayCache();
        
        $this->assertInstanceOf(\App\Contracts\CacheInterface::class, $cache);
        
        // Test cache operations
        $cache->set('test_key', 'test_value', 3600);
        
        $this->assertTrue($cache->has('test_key'));
        $this->assertEquals('test_value', $cache->get('test_key'));
        
        $success = $cache->delete('test_key');
        $this->assertTrue($success);
        $this->assertFalse($cache->has('test_key'));
        
        // Test creating a user service
        $userService = MockFactory::createUserService();
        
        // Test that it has the LoggerTrait and TimestampableTrait methods
        $this->assertTrue(method_exists($userService, 'info'));
        $this->assertTrue(method_exists($userService, 'updateTimestamps'));
        
        // Test creating a user
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ];
        
        $user = $userService->createUser($userData);
        
        $this->assertIsArray($user);
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('name', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('created_at', $user);
        $this->assertArrayHasKey('updated_at', $user);
    }
}