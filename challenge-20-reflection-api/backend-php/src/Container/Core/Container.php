<?php

namespace App\Container\Core;

use App\Container\Contracts\ContainerInterface;
use App\Container\Exception\NotFoundException;

class Container implements ContainerInterface
{
    private array $services = [];
    private array $resolved = [];
    private array $providers = [];

    public function register(string $id, callable $resolver): void
    {
        $this->services[$id] = $resolver;
        unset($this->resolved[$id]);
    }

    public function get(string $id)
    {
        // Return already resolved instance
        if (isset($this->resolved[$id])) {
            return $this->resolved[$id];
        }

        // Check if service exists
        if (!isset($this->services[$id])) {
            throw new NotFoundException($id);
        }

        // Resolve the service
        $resolver = $this->services[$id];
        $instance = $resolver($this);

        // Store resolved instance
        $this->resolved[$id] = $instance;

        return $instance;
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }

    public function registerProvider(object $provider): void
    {
        $this->providers[] = $provider;
        
        // Register services from provider
        if (method_exists($provider, 'register')) {
            $provider->register($this);
        }
    }

    public function getRegisteredServices(): array
    {
        return array_keys($this->services);
    }

    public function getProviders(): array
    {
        return $this->providers;
    }
}