<?php

namespace Tests;

use App\Traits\LoggerTrait;
use App\Traits\CacheableTrait;
use App\Traits\ValidatableTrait;
use PHPUnit\Framework\TestCase;

class TraitTest extends TestCase
{
    public function testLoggerTrait(): void
    {
        // Create a class that uses the LoggerTrait
        $logger = new class {
            use LoggerTrait;
        };
        
        // Test that the logger can be configured
        $logger->setLogLevel('debug');
        $logger->setLogFile('test.log');
        
        // Test logging (this will create a file)
        $logger->info('Test message');
        $logger->error('Error message');
        
        // Verify the log file was created
        $this->assertFileExists('test.log');
        
        // Clean up
        unlink('test.log');
    }
    
    public function testCacheableTrait(): void
    {
        // Create a class that uses the CacheableTrait
        $cacheable = new class {
            use CacheableTrait;
        };
        
        // Test setting cache TTL
        $cacheable->setCacheTtl(1800); // 30 minutes
        
        // Test caching data
        $result = $cacheable->getFromCache('test_key', function() {
            return 'cached_value';
        });
        
        $this->assertEquals('cached_value', $result);
        
        // Test getting cached data again (should return the same value)
        $result2 = $cacheable->getFromCache('test_key', function() {
            return 'different_value';
        });
        
        $this->assertEquals('cached_value', $result2);
        
        // Test cache stats
        $stats = $cacheable->getCacheStats();
        $this->assertArrayHasKey('count', $stats);
        $this->assertArrayHasKey('keys', $stats);
    }
    
    public function testValidatableTrait(): void
    {
        // Create a class that uses the ValidatableTrait
        $validatable = new class {
            use ValidatableTrait;
        };
        
        // Set validation rules
        $validatable->setValidationRules([
            'email' => ['required', 'email'],
            'age' => ['required', 'numeric']
        ]);
        
        // Test valid data
        $validData = [
            'email' => 'test@example.com',
            'age' => '25'
        ];
        
        $isValid = $validatable->validate($validData);
        $this->assertTrue($isValid);
        $this->assertFalse($validatable->hasErrors());
        
        // Test invalid data
        $invalidData = [
            'email' => 'invalid-email',
            'age' => 'twenty-five'
        ];
        
        $isValid = $validatable->validate($invalidData);
        $this->assertFalse($isValid);
        $this->assertTrue($validatable->hasErrors());
        
        // Check specific errors
        $errors = $validatable->getErrors();
        $this->assertArrayHasKey('email', $errors);
        $this->assertArrayHasKey('age', $errors);
    }
}