<?php

namespace App\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Magic\FluentInterface;
use App\Magic\DynamicProxy;
use App\Magic\MethodInterceptor;

class MagicController
{
    public function __call(string $method, array $parameters)
    {
        // Handle dynamic action methods
        if (strpos($method, 'action') === 0) {
            $action = lcfirst(substr($method, 6));
            return $this->$action(...$parameters);
        }
        
        throw new \BadMethodCallException("Method {$method} does not exist.");
    }

    public function index(Request $request): Response
    {
        return new Response([
            'message' => 'Magic Methods Demo',
            'endpoints' => [
                'GET /api/magic/fluent' => 'Fluent Interface Demo',
                'GET /api/magic/proxy' => 'Dynamic Proxy Demo',
                'GET /api/magic/interceptor' => 'Method Interceptor Demo',
            ]
        ]);
    }

    public function fluent(Request $request): Response
    {
        $fluent = new FluentInterface();
        
        // Demonstrate fluent interface
        $fluent->setName('John Doe')
               ->setEmail('john@example.com')
               ->setAge(30)
               ->addRole('user')
               ->addRole('admin');
        
        return new Response([
            'status' => 'success',
            'data' => $fluent->execute()
        ]);
    }

    public function proxy(Request $request): Response
    {
        $target = new class {
            public function greet(string $name): string
            {
                return "Hello, {$name}!";
            }
            
            public function calculate(int $a, int $b): int
            {
                return $a + $b;
            }
        };
        
        $proxy = new DynamicProxy($target);
        
        // Add an interceptor
        $proxy->addInterceptor('greet', function ($target, $method, $args) {
            $result = $target->$method(...$args);
            return strtoupper($result);
        });
        
        $greeting = $proxy->greet('World');
        $sum = $proxy->calculate(5, 3);
        
        return new Response([
            'status' => 'success',
            'data' => [
                'greeting' => $greeting,
                'sum' => $sum
            ]
        ]);
    }

    public function interceptor(Request $request): Response
    {
        $target = new class {
            public function processData(array $data): array
            {
                return array_map('strtoupper', $data);
            }
        };
        
        $interceptor = new MethodInterceptor();
        
        // Add interceptors
        $interceptor->beforeProcessData(function ($target, $method, $args) {
            echo "Before processing data\n";
        });
        
        $interceptor->afterProcessData(function ($target, $method, $args, $result) {
            echo "After processing data\n";
        });
        
        $interceptor->aroundProcessData(function ($target, $method, $args, $proceed) {
            echo "Around processing data - before\n";
            $result = $proceed($target, $method, $args);
            echo "Around processing data - after\n";
            return $result;
        });
        
        $data = ['hello', 'world'];
        $result = $interceptor->intercept($target, 'processData', [$data]);
        
        return new Response([
            'status' => 'success',
            'data' => $result
        ]);
    }

    public function __get(string $property)
    {
        return $this->$property ?? null;
    }

    public function __set(string $property, $value): void
    {
        $this->$property = $value;
    }

    public function __isset(string $property): bool
    {
        return isset($this->$property);
    }

    public function __unset(string $property): void
    {
        unset($this->$property);
    }

    public function __toString(): string
    {
        return "MagicController";
    }
}