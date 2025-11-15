<?php

return [
    'app' => [
        'name' => 'PHP Challenge',
        'debug' => true,
        'environment' => 'development',
        'version' => '1.0.0',
    ],
    
    'database' => [
        'driver' => 'mysql',
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? 3306,
        'database' => $_ENV['DB_NAME'] ?? 'challenge_db',
        'username' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASS'] ?? '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ],
    ],
    
    'auth' => [
        'secret_key' => $_ENV['JWT_SECRET'] ?? 'change-me-in-production',
        'token_lifetime' => 3600, // 1 hour
        'refresh_token_lifetime' => 604800, // 7 days
        'algorithm' => 'HS256',
    ],
    
    'storage' => [
        'path' => __DIR__ . '/../storage',
        'temp_path' => __DIR__ . '/../storage/temp',
        'uploads_path' => __DIR__ . '/../storage/uploads',
    ],
    
    'logging' => [
        'path' => __DIR__ . '/../logs',
        'level' => 'debug',
        'max_files' => 10,
    ],
    
    'cache' => [
        'driver' => 'redis',
        'host' => $_ENV['REDIS_HOST'] ?? 'localhost',
        'port' => $_ENV['REDIS_PORT'] ?? 6379,
        'password' => $_ENV['REDIS_PASSWORD'] ?? null,
        'database' => 0,
    ],
    
    'queue' => [
        'driver' => 'redis',
        'host' => $_ENV['REDIS_HOST'] ?? 'localhost',
        'port' => $_ENV['REDIS_PORT'] ?? 6379,
        'password' => $_ENV['REDIS_PASSWORD'] ?? null,
        'database' => 1,
    ],
    
    'routes' => array_merge([
        // Default routes will be defined in challenge-specific files
    ], require __DIR__ . '/routes.php'),
];