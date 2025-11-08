<?php

namespace Tests\Unit;

use App\Recovery\CircuitBreaker;
use PHPUnit\Framework\TestCase;
use Exception;

class CircuitBreakerTest extends TestCase
{
    public function testCircuitBreakerClosedState()
    {
        $breaker = new CircuitBreaker('test', 5, 60);
        
        $this->assertEquals('closed', $breaker->getState());
        
        $result = $breaker->execute(function() {
            return 'success';
        });
        
        $this->assertEquals('success', $result);
        $this->assertEquals('closed', $breaker->getState());
    }

    public function testCircuitBreakerOpenState()
    {
        $breaker = new CircuitBreaker('test', 2, 60);
        
        // Cause failures to open the circuit
        try {
            $breaker->execute(function() { throw new Exception('Failure 1'); });
        } catch (Exception $e) {
            // Expected
        }
        
        try {
            $breaker->execute(function() { throw new Exception('Failure 2'); });
        } catch (Exception $e) {
            // Expected
        }
        
        $this->assertEquals('open', $breaker->getState());
        
        // Next call should throw exception due to open circuit
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Circuit breaker 'test' is OPEN");
        
        $breaker->execute(function() {
            return 'should not execute';
        });
    }

    public function testCircuitBreakerHalfOpenState()
    {
        $breaker = new CircuitBreaker('test', 1, 1); // 1 second timeout for testing
        
        // Cause failure to open circuit
        try {
            $breaker->execute(function() { throw new Exception('Failure'); });
        } catch (Exception $e) {
            // Expected
        }
        
        $this->assertEquals('open', $breaker->getState());
        
        // Wait for timeout to pass
        sleep(2);
        
        // Now in half-open state, first call should be allowed
        $this->expectException(Exception::class);
        $breaker->execute(function() { throw new Exception('Failure in half-open'); });
        
        // Circuit should be open again
        $this->assertEquals('open', $breaker->getState());
    }

    public function testCircuitBreakerReset()
    {
        $breaker = new CircuitBreaker('test', 1, 60);
        
        // Cause failure to open circuit
        try {
            $breaker->execute(function() { throw new Exception('Failure'); });
        } catch (Exception $e) {
            // Expected
        }
        
        $this->assertEquals('open', $breaker->getState());
        
        // Reset the circuit breaker
        $breaker->reset();
        
        $this->assertEquals('closed', $breaker->getState());
        $this->assertEquals(0, $breaker->getFailureCount());
    }

    public function testCircuitBreakerSuccessInHalfOpen()
    {
        $breaker = new CircuitBreaker('test', 1, 1);
        
        // Cause failure to open circuit
        try {
            $breaker->execute(function() { throw new Exception('Failure'); });
        } catch (Exception $e) {
            // Expected
        }
        
        $this->assertEquals('open', $breaker->getState());
        
        // Wait for timeout
        sleep(2);
        
        // Success in half-open state should close the circuit
        $result = $breaker->execute(function() { return 'success'; });
        
        $this->assertEquals('success', $result);
        $this->assertEquals('closed', $breaker->getState());
    }
}