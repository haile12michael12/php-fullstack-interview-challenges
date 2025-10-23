<?php

return [
    'app' => [
        'name' => 'Challenge 14 - WebSocket Real-time Dashboard',
        'debug' => $_ENV['APP_DEBUG'] ?? true,
        'environment' => $_ENV['APP_ENV'] ?? 'development',
    ],
    
    'websocket' => [
        'host' => $_ENV['WEBSOCKET_HOST'] ?? 'localhost',
        'port' => $_ENV['WEBSOCKET_PORT'] ?? 8080,
    ],
    
    'database' => [
        'driver' => 'mysql',
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? 3306,
        'database' => $_ENV['DB_NAME'] ?? 'challenge_14_db',
        'username' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASS'] ?? '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],
    
    'storage' => [
        'path' => __DIR__ . '/../storage',
    ],
    
    'logging' => [
        'path' => __DIR__ . '/../logs',
        'level' => 'debug',
    ],
];