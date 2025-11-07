<?php

use DI\ContainerBuilder;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

require_once __DIR__ . '/../vendor/autoload.php';

// Create a container builder
$containerBuilder = new ContainerBuilder();

// Configure the container
$containerBuilder->addDefinitions([
    // Logger
    Logger::class => function () {
        $logger = new Logger('user-service');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../../var/log/app.log', Logger::DEBUG));
        return $logger;
    },
    
    // Database connection
    PDO::class => function () {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $dbname = $_ENV['DB_NAME'] ?? 'user_db';
        $username = $_ENV['DB_USER'] ?? 'root';
        $password = $_ENV['DB_PASS'] ?? '';
        
        return new PDO("mysql:host=$host;dbname=$dbname", $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
]);

// Build and return the container
return $containerBuilder->build();