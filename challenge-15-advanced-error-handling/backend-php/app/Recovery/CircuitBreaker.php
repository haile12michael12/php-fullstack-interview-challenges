<?php

namespace App\Recovery;

use App\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;
use DateInterval;
use DateTime;

class CircuitBreaker
{
    private LoggerInterface $logger;
    private string $name;
    private int $failureThreshold;
    private int $timeout; // in seconds
    private int $failureCount;
    private ?DateTime $lastFailureTime;
    private string $state;
    
    // States
    private const STATE_CLOSED = 'closed';
    private const STATE_OPEN = 'open';
    private const STATE_HALF_OPEN = 'half_open';

    public function __construct(
        string $name,
        int $failureThreshold = 5,
        int $timeout = 60
    ) {
        $this->logger = LoggerFactory::create('circuit_breaker');
        $this->name = $name;
        $this->failureThreshold = $failureThreshold;
        $this->timeout = $timeout;
        $this->failureCount = 0;
        $this->lastFailureTime = null;
        $this->state = self::STATE_CLOSED;
    }

    /**
     * Execute a callable with circuit breaker protection
     *
     * @param callable $callable The function to execute
     * @return mixed
     * @throws \Exception
     */
    public function execute(callable $callable)
    {
        if ($this->isOpen()) {
            $this->logger->warning('Circuit breaker is OPEN, rejecting call', [
                'circuit' => $this->name,
                'failure_count' => $this->failureCount,
                'last_failure' => $this->lastFailureTime?->format('c')
            ]);
            
            throw new \Exception("Circuit breaker '{$this->name}' is OPEN");
        }

        try {
            $result = $callable();
            $this->onSuccess();
            return $result;
        } catch (\Throwable $exception) {
            $this->onFailure($exception);
            throw $exception;
        }
    }

    private function isOpen(): bool
    {
        switch ($this->state) {
            case self::STATE_OPEN:
                // Check if timeout has passed
                if ($this->lastFailureTime) {
                    $now = new DateTime();
                    $timeoutTime = clone $this->lastFailureTime;
                    $timeoutTime->add(new DateInterval("PT{$this->timeout}S"));
                    
                    if ($now >= $timeoutTime) {
                        // Move to half-open state to test service
                        $this->state = self::STATE_HALF_OPEN;
                        $this->logger->info('Circuit breaker moving to HALF_OPEN state', [
                            'circuit' => $this->name
                        ]);
                        return false;
                    }
                }
                return true;
                
            case self::STATE_HALF_OPEN:
                // In half-open state, we allow one request through
                return false;
                
            case self::STATE_CLOSED:
            default:
                return false;
        }
    }

    private function onSuccess(): void
    {
        if ($this->state === self::STATE_HALF_OPEN) {
            // Success in half-open state means we can close the circuit
            $this->state = self::STATE_CLOSED;
            $this->failureCount = 0;
            $this->lastFailureTime = null;
            
            $this->logger->info('Circuit breaker CLOSED after successful test', [
                'circuit' => $this->name
            ]);
        } elseif ($this->failureCount > 0) {
            // Reset failure count on success
            $this->failureCount = 0;
            $this->lastFailureTime = null;
        }
    }

    private function onFailure(\Throwable $exception): void
    {
        $this->failureCount++;
        $this->lastFailureTime = new DateTime();
        
        $this->logger->warning('Circuit breaker recorded failure', [
            'circuit' => $this->name,
            'failure_count' => $this->failureCount,
            'exception' => get_class($exception),
            'message' => $exception->getMessage()
        ]);

        if ($this->failureCount >= $this->failureThreshold) {
            $this->state = self::STATE_OPEN;
            $this->logger->error('Circuit breaker OPENED due to failures', [
                'circuit' => $this->name,
                'failure_count' => $this->failureCount,
                'threshold' => $this->failureThreshold
            ]);
        } elseif ($this->state === self::STATE_HALF_OPEN) {
            // Failure in half-open state means we go back to open
            $this->state = self::STATE_OPEN;
            $this->logger->error('Circuit breaker returned to OPEN after test failure', [
                'circuit' => $this->name
            ]);
        }
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getFailureCount(): int
    {
        return $this->failureCount;
    }

    public function getLastFailureTime(): ?DateTime
    {
        return $this->lastFailureTime;
    }

    public function reset(): void
    {
        $this->failureCount = 0;
        $this->lastFailureTime = null;
        $this->state = self::STATE_CLOSED;
        
        $this->logger->info('Circuit breaker reset', [
            'circuit' => $this->name
        ]);
    }
}