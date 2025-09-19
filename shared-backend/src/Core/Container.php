<?php

declare(strict_types=1);

namespace SharedBackend\Core;

use League\Container\Container as LeagueContainer;
use League\Container\ReflectionContainer;
use Psr\Container\ContainerInterface;
use SharedBackend\Core\Exceptions\ContainerException;
use SharedBackend\Core\Exceptions\NotFoundException;

/**
 * Advanced dependency injection container with auto-wiring and service definitions
 */
class Container implements ContainerInterface
{
    private LeagueContainer $container;
    private array $services = [];
    private array $singletons = [];

    public function __construct()
    {
        $this->container = new LeagueContainer();
        $this->container->delegate(new ReflectionContainer(true));
        $this->registerCoreServices();
    }

    public function get(string $id): mixed
    {
        try {
            if (isset($this->singletons[$id])) {
                return $this->singletons[$id];
            }

            if ($this->container->has($id)) {
                $service = $this->container->get($id);
                if (isset($this->services[$id]) && $this->services[$id]['singleton']) {
                    $this->singletons[$id] = $service;
                }
                return $service;
            }

            throw new NotFoundException("Service '{$id}' not found");
        } catch (\Exception $e) {
            throw new ContainerException("Error resolving service '{$id}': " . $e->getMessage(), 0, $e);
        }
    }

    public function has(string $id): bool
    {
        return $this->container->has($id) || isset($this->services[$id]);
    }

    public function bind(string $id, $concrete = null, bool $singleton = false): void
    {
        $this->services[$id] = [
            'concrete' => $concrete ?? $id,
            'singleton' => $singleton
        ];

        if ($concrete) {
            $this->container->add($id, $concrete, $singleton);
        }
    }

    public function singleton(string $id, $concrete = null): void
    {
        $this->bind($id, $concrete, true);
    }

    public function instance(string $id, mixed $instance): void
    {
        $this->singletons[$id] = $instance;
        $this->services[$id] = [
            'concrete' => null,
            'singleton' => true
        ];
    }

    public function tag(string $tag, array $services): void
    {
        $this->container->inflector($tag, function ($instance) {
            // Tag-based service resolution
            return $instance;
        });
    }

    public function resolve(string $class): mixed
    {
        try {
            return $this->container->get($class);
        } catch (\Exception $e) {
            throw new ContainerException("Could not resolve class '{$class}': " . $e->getMessage(), 0, $e);
        }
    }

    private function registerCoreServices(): void
    {
        // Register core services
        $this->singleton(ContainerInterface::class, $this);
        $this->singleton(Config::class);
        $this->singleton(Logger::class);
        $this->singleton(Database::class);
        $this->singleton(Cache::class);
        $this->singleton(EventDispatcher::class);
    }
}
