<?php

namespace App\Container\Core;

class ServiceRegistry
{
    private static array $services = [];

    public static function register(string $id, callable $factory): void
    {
        self::$services[$id] = $factory;
    }

    public static function get(string $id)
    {
        if (!isset(self::$services[$id])) {
            throw new \InvalidArgumentException("Service {$id} not registered");
        }

        $factory = self::$services[$id];
        return $factory();
    }

    public static function has(string $id): bool
    {
        return isset(self::$services[$id]);
    }

    public static function getAll(): array
    {
        return array_keys(self::$services);
    }

    public static function clear(): void
    {
        self::$services = [];
    }
}