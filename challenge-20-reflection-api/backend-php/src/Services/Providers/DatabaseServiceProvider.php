<?php

namespace App\Services\Providers;

use App\Container\Contracts\ContainerInterface;
use App\Services\Implementations\DatabaseLogger;
use PDO;

class DatabaseServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        $container->register(PDO::class, function () {
            // In a real application, these would come from configuration
            $dsn = 'mysql:host=localhost;dbname=reflection_api;charset=utf8mb4';
            $username = 'root';
            $password = '';
            return new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        });
        
        $container->register(DatabaseLogger::class, function (ContainerInterface $container) {
            $pdo = $container->get(PDO::class);
            return new DatabaseLogger($pdo);
        });
    }
}