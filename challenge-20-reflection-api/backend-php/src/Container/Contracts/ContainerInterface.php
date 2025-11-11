<?php

namespace App\Container\Contracts;

interface ContainerInterface
{
    /**
     * Register a service in the container
     */
    public function register(string $id, callable $resolver): void;

    /**
     * Get a service from the container
     */
    public function get(string $id);

    /**
     * Check if a service exists in the container
     */
    public function has(string $id): bool;

    /**
     * Register a service provider
     */
    public function registerProvider(object $provider): void;

    /**
     * Get all registered service IDs
     */
    public function getRegisteredServices(): array;
}