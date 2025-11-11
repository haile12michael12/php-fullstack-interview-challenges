<?php

namespace App\Service;

use App\Factory\AnonymousClassFactory;
use App\Factory\MockFactory;
use App\Contracts\StrategyInterface;

class BusinessLogicService
{
    private array $strategies = [];
    
    public function __construct()
    {
        // Initialize with factory-created anonymous classes
        $this->strategies['logger'] = AnonymousClassFactory::createLoggerStrategy();
        $this->cacheableCalculator = AnonymousClassFactory::createCacheableCalculator();
        $this->validatableEntity = AnonymousClassFactory::createValidatableEntity();
        $this->arrayCache = MockFactory::createArrayCache();
        $this->userService = MockFactory::createUserService();
    }
    
    public function executeStrategy(string $strategyName, array $data): mixed
    {
        if (!isset($this->strategies[$strategyName])) {
            throw new \InvalidArgumentException("Strategy {$strategyName} not found");
        }
        
        return $this->strategies[$strategyName]->execute($data);
    }
    
    public function getAvailableStrategies(): array
    {
        return array_map(function(StrategyInterface $strategy) {
            return $strategy->getName();
        }, $this->strategies);
    }
    
    public function performCalculation(int $number): int
    {
        return $this->cacheableCalculator->calculateExpensiveOperation($number);
    }
    
    public function validateEntity(array $data): array
    {
        return $this->validatableEntity->process($data);
    }
    
    public function cacheData(string $key, $value, int $ttl = 3600): bool
    {
        return $this->arrayCache->set($key, $value, $ttl);
    }
    
    public function getCachedData(string $key)
    {
        return $this->arrayCache->get($key);
    }
    
    public function createUser(array $userData): array
    {
        return $this->userService->createUser($userData);
    }
    
    public function getUserStats(): array
    {
        return [
            'users' => $this->userService->getUsers(),
            'cache_stats' => $this->arrayCache->getStats(),
            'strategies' => $this->getAvailableStrategies()
        ];
    }
}