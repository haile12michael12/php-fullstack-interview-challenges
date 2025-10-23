<?php

return [
    'app' => [
        'name' => 'Challenge 13 - GraphQL API with Subscriptions',
        'debug' => $_ENV['APP_DEBUG'] ?? true,
        'environment' => $_ENV['APP_ENV'] ?? 'development',
    ],
    
    'database' => [
        'driver' => 'mysql',
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? 3306,
        'database' => $_ENV['DB_NAME'] ?? 'challenge_13_db',
        'username' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASS'] ?? '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],
    
    'graphql' => [
        'port' => $_ENV['GRAPHQL_PORT'] ?? 8080,
        'schema_path' => __DIR__ . '/../graphql/schema.graphql',
    ],
    
    'storage' => [
        'path' => __DIR__ . '/../storage',
    ],
    
    'logging' => [
        'path' => __DIR__ . '/../logs',
        'level' => 'debug',
    ],
];