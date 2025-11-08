<?php

namespace Tests\Unit;

use App\Recovery\RetryMechanism;
use App\Exception\ExternalServiceException;
use PHPUnit\Framework\TestCase;
use Exception;

class RetryMechanismTest extends TestCase
{
    public function testExecuteWithNoExceptions()
    {
        $retry = new RetryMechanism(3, 100, 1.0);
        
        $result = $retry->execute(function() {
            return 'success';
        });
        
        $this->assertEquals('success', $result);
    }

    public function testExecuteWithRetryableException()
    {
        $retry = new RetryMechanism(3, 10, 1.0);
        $attempts = 0;
        
        $result = $retry->execute(function() use (&$attempts) {
            $attempts++;
            if ($attempts < 3) {
                throw new ExternalServiceException('Temporary failure');
            }
            return 'success';
        }, [ExternalServiceException::class]);
        
        $this->assertEquals('success', $result);
        $this->assertEquals(3, $attempts);
    }

    public function testExecuteWithNonRetryableException()
    {
        $retry = new RetryMechanism(3, 10, 1.0);
        
        $this->expectException(Exception::class);
        
        $retry->execute(function() {
            throw new Exception('Non-retryable exception');
        }, [ExternalServiceException::class]);
    }

    public function testExecuteWithMaxRetriesExceeded()
    {
        $retry = new RetryMechanism(2, 10, 1.0);
        
        $this->expectException(ExternalServiceException::class);
        
        $retry->execute(function() {
            throw new ExternalServiceException('Persistent failure');
        }, [ExternalServiceException::class]);
    }

    public function testCalculateDelay()
    {
        $retry = new RetryMechanism(3, 1000, 2.0);
        
        // Test that we can call the method (private method testing through reflection)
        $reflection = new \ReflectionClass($retry);
        $method = $reflection->getMethod('calculateDelay');
        $method->setAccessible(true);
        
        $delay1 = $method->invokeArgs($retry, [1]);
        $delay2 = $method->invokeArgs($retry, [2]);
        $delay3 = $method->invokeArgs($retry, [3]);
        
        // Delays should be increasing due to exponential backoff
        $this->assertGreaterThanOrEqual($delay1, $delay2);
        $this->assertGreaterThanOrEqual($delay2, $delay3);
    }
}