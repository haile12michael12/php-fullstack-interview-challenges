<?php

return [
    'default' => $_ENV['DB_CONNECTION'] ?? 'mysql',
    
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'url' => $_ENV['DATABASE_URL'] ?? null,
            'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
            'port' => $_ENV['DB_PORT'] ?? '3306',
            'database' => $_ENV['DB_DATABASE'] ?? 'grpc_chat',
            'username' => $_ENV['DB_USERNAME'] ?? 'root',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'unix_socket' => $_ENV['DB_SOCKET'] ?? '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => $_ENV['MYSQL_ATTR_SSL_CA'] ?? null,
            ]) : [],
        ],
        
        'sqlite' => [
            'driver' => 'sqlite',
            'url' => $_ENV['DATABASE_URL'] ?? null,
            'database' => $_ENV['DB_DATABASE'] ?? __DIR__ . '/../storage/database.sqlite',
            'prefix' => '',
            'foreign_key_constraints' => $_ENV['DB_FOREIGN_KEYS'] ?? true,
        ],
    ],
    
    'migrations' => 'migrations',
    
    'redis' => [
        'client' => $_ENV['REDIS_CLIENT'] ?? 'phpredis',
        
        'options' => [
            'cluster' => $_ENV['REDIS_CLUSTER'] ?? 'redis',
            'prefix' => $_ENV['REDIS_PREFIX'] ?? 'grpc_chat_database_',
        ],
        
        'default' => [
            'url' => $_ENV['REDIS_URL'] ?? null,
            'host' => $_ENV['REDIS_HOST'] ?? '127.0.0.1',
            'password' => $_ENV['REDIS_PASSWORD'] ?? null,
            'port' => $_ENV['REDIS_PORT'] ?? '6379',
            'database' => $_ENV['REDIS_DB'] ?? '0',
        ],
        
        'cache' => [
            'url' => $_ENV['REDIS_URL'] ?? null,
            'host' => $_ENV['REDIS_HOST'] ?? '127.0.0.1',
            'password' => $_ENV['REDIS_PASSWORD'] ?? null,
            'port' => $_ENV['REDIS_PORT'] ?? '6379',
            'database' => $_ENV['REDIS_CACHE_DB'] ?? '1',
        ],
    ],
];