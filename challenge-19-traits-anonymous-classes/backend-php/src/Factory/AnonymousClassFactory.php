<?php

namespace App\Factory;

use App\Contracts\StrategyInterface;
use App\Traits\LoggerTrait;
use App\Traits\CacheableTrait;

class AnonymousClassFactory
{
    public static function createLoggerStrategy(): StrategyInterface
    {
        return new class implements StrategyInterface {
            use LoggerTrait;

            public function execute(array $data): mixed
            {
                $message = $data['message'] ?? 'No message provided';
                $level = $data['level'] ?? 'info';
                
                $this->log($message, $level);
                
                return [
                    'status' => 'logged',
                    'message' => $message,
                    'level' => $level
                ];
            }

            public function getName(): string
            {
                return 'logger_strategy';
            }
        };
    }

    public static function createCacheableCalculator(): object
    {
        return new class {
            use CacheableTrait;

            public function calculateExpensiveOperation(int $number): int
            {
                return $this->getFromCache("calc_{$number}", function() use ($number) {
                    // Simulate expensive operation
                    sleep(1);
                    return $number * $number * $number;
                });
            }
        };
    }

    public static function createValidatableEntity(): object
    {
        return new class {
            use \App\Traits\ValidatableTrait;

            public function __construct()
            {
                $this->setValidationRules([
                    'email' => ['required', 'email'],
                    'age' => ['required', 'numeric']
                ]);
            }

            public function process(array $data): array
            {
                if ($this->validate($data)) {
                    return [
                        'status' => 'success',
                        'data' => $data
                    ];
                } else {
                    return [
                        'status' => 'error',
                        'errors' => $this->getErrors()
                    ];
                }
            }
        };
    }
}