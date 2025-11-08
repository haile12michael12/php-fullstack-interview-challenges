<?php

namespace App\Recovery;

use App\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;
use Throwable;

class FallbackStrategy
{
    private LoggerInterface $logger;
    private array $strategies;

    public function __construct()
    {
        $this->logger = LoggerFactory::create('fallback');
        $this->strategies = [];
    }

    /**
     * Add a fallback strategy for a specific exception
     *
     * @param string $exceptionClass The exception class to handle
     * @param callable $strategy The fallback strategy callable
     * @return self
     */
    public function addStrategy(string $exceptionClass, callable $strategy): self
    {
        $this->strategies[$exceptionClass] = $strategy;
        return $this;
    }

    /**
     * Add a default fallback strategy
     *
     * @param callable $strategy The fallback strategy callable
     * @return self
     */
    public function addDefaultStrategy(callable $strategy): self
    {
        $this->strategies['default'] = $strategy;
        return $this;
    }

    /**
     * Execute a callable with fallback strategies
     *
     * @param callable $callable The primary callable to execute
     * @param array $context Additional context for strategies
     * @return mixed
     */
    public function execute(callable $callable, array $context = [])
    {
        try {
            return $callable();
        } catch (Throwable $exception) {
            return $this->handleWithFallback($exception, $context);
        }
    }

    /**
     * Handle exception with appropriate fallback strategy
     *
     * @param Throwable $exception The thrown exception
     * @param array $context Additional context
     * @return mixed
     */
    private function handleWithFallback(Throwable $exception, array $context = [])
    {
        $exceptionClass = get_class($exception);
        
        // Try to find specific strategy
        if (isset($this->strategies[$exceptionClass])) {
            $this->logger->info('Using specific fallback strategy', [
                'exception' => $exceptionClass,
                'context' => $context
            ]);
            
            return $this->strategies[$exceptionClass]($exception, $context);
        }
        
        // Try to find strategy for parent classes
        foreach ($this->strategies as $class => $strategy) {
            if ($class !== 'default' && is_a($exceptionClass, $class, true)) {
                $this->logger->info('Using inherited fallback strategy', [
                    'exception' => $exceptionClass,
                    'strategy_class' => $class,
                    'context' => $context
                ]);
                
                return $strategy($exception, $context);
            }
        }
        
        // Use default strategy if available
        if (isset($this->strategies['default'])) {
            $this->logger->info('Using default fallback strategy', [
                'exception' => $exceptionClass,
                'context' => $context
            ]);
            
            return $this->strategies['default']($exception, $context);
        }
        
        // No fallback strategy available, rethrow
        $this->logger->warning('No fallback strategy available, rethrowing exception', [
            'exception' => $exceptionClass,
            'message' => $exception->getMessage()
        ]);
        
        throw $exception;
    }

    /**
     * Create a cache fallback strategy
     *
     * @param mixed $cachedValue The cached value to return
     * @return callable
     */
    public static function cacheFallback($cachedValue): callable
    {
        return function () use ($cachedValue) {
            return $cachedValue;
        };
    }

    /**
     * Create a default value fallback strategy
     *
     * @param mixed $defaultValue The default value to return
     * @return callable
     */
    public static function defaultFallback($defaultValue): callable
    {
        return function () use ($defaultValue) {
            return $defaultValue;
        };
    }

    /**
     * Create a null fallback strategy
     *
     * @return callable
     */
    public static function nullFallback(): callable
    {
        return function () {
            return null;
        };
    }
}