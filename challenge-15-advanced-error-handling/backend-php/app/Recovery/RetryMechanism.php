<?php

namespace App\Recovery;

use App\Exception\ExternalServiceException;
use App\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;
use Exception;
use Throwable;

class RetryMechanism
{
    private LoggerInterface $logger;
    private int $maxRetries;
    private int $baseDelay; // in milliseconds
    private float $backoffMultiplier;

    public function __construct(
        int $maxRetries = 3,
        int $baseDelay = 1000,
        float $backoffMultiplier = 2.0
    ) {
        $this->logger = LoggerFactory::create('retry');
        $this->maxRetries = $maxRetries;
        $this->baseDelay = $baseDelay;
        $this->backoffMultiplier = $backoffMultiplier;
    }

    /**
     * Execute a callable with retry logic
     *
     * @param callable $callable The function to execute
     * @param array $retryOn Exception classes to retry on
     * @return mixed
     * @throws Throwable
     */
    public function execute(callable $callable, array $retryOn = [])
    {
        $attempt = 0;
        $lastException = null;

        while ($attempt <= $this->maxRetries) {
            try {
                return $callable();
            } catch (Throwable $exception) {
                $lastException = $exception;
                $attempt++;

                // Check if we should retry on this exception
                if (!$this->shouldRetry($exception, $retryOn)) {
                    $this->logger->warning('Not retrying on exception', [
                        'exception' => get_class($exception),
                        'message' => $exception->getMessage()
                    ]);
                    throw $exception;
                }

                // If we've exhausted retries, throw the last exception
                if ($attempt > $this->maxRetries) {
                    $this->logger->error('Max retries exceeded', [
                        'attempts' => $attempt,
                        'exception' => get_class($exception),
                        'message' => $exception->getMessage()
                    ]);
                    throw $exception;
                }

                // Calculate delay using exponential backoff
                $delay = $this->calculateDelay($attempt);
                
                $this->logger->warning('Retrying after exception', [
                    'attempt' => $attempt,
                    'max_retries' => $this->maxRetries,
                    'delay' => $delay,
                    'exception' => get_class($exception),
                    'message' => $exception->getMessage()
                ]);

                // Wait before retrying
                usleep($delay * 1000); // usleep uses microseconds
            }
        }

        // This should never be reached, but just in case
        throw $lastException;
    }

    private function shouldRetry(Throwable $exception, array $retryOn): bool
    {
        // If no specific exceptions specified, retry on all exceptions
        if (empty($retryOn)) {
            return true;
        }

        // Check if exception is in retry list
        foreach ($retryOn as $exceptionClass) {
            if ($exception instanceof $exceptionClass) {
                return true;
            }
        }

        return false;
    }

    private function calculateDelay(int $attempt): int
    {
        // Exponential backoff with jitter
        $delay = $this->baseDelay * pow($this->backoffMultiplier, $attempt - 1);
        
        // Add jitter (±25%)
        $jitter = $delay * 0.25 * (mt_rand() / mt_getrandmax());
        $delay += $jitter * (mt_rand(0, 1) * 2 - 1); // Randomly add or subtract
        
        return (int) max(0, $delay);
    }

    public function setMaxRetries(int $maxRetries): void
    {
        $this->maxRetries = $maxRetries;
    }

    public function setBaseDelay(int $baseDelay): void
    {
        $this->baseDelay = $baseDelay;
    }

    public function setBackoffMultiplier(float $backoffMultiplier): void
    {
        $this->backoffMultiplier = $backoffMultiplier;
    }
}