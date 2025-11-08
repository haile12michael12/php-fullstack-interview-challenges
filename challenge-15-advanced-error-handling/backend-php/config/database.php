<?php

return [
    'default' => $_ENV['DB_CONNECTION'] ?? 'mysql',
    
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'url' => $_ENV['DATABASE_URL'] ?? null,
            'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
            'port' => $_ENV['DB_PORT'] ?? '3306',
            'database' => $_ENV['DB_DATABASE'] ?? 'forge',
            'username' => $_ENV['DB_USERNAME'] ?? 'forge',
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
        
        'pgsql' => [
            'driver' => 'pgsql',
            'url' => $_ENV['DATABASE_URL'] ?? null,
            'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
            'port' => $_ENV['DB_PORT'] ?? '5432',
            'database' => $_ENV['DB_DATABASE'] ?? 'forge',
            'username' => $_ENV['DB_USERNAME'] ?? 'forge',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],
        
        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => $_ENV['DATABASE_URL'] ?? null,
            'host' => $_ENV['DB_HOST'] ?? 'localhost',
            'port' => $_ENV['DB_PORT'] ?? '1433',
            'database' => $_ENV['DB_DATABASE'] ?? 'forge',
            'username' => $_ENV['DB_USERNAME'] ?? 'forge',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],
    ],
    
    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],
    
    'redis' => [
        'client' => $_ENV['REDIS_CLIENT'] ?? 'phpredis',
        
        'options' => [
            'cluster' => $_ENV['REDIS_CLUSTER'] ?? 'redis',
            'prefix' => $_ENV['REDIS_PREFIX'] ?? 'challenge15_database_',
        ],
        
        'default' => [
            'url' => $_ENV['REDIS_URL'] ?? null,
            'host' => $_ENV['REDIS_HOST'] ?? '127.0.0.1',
            'username' => $_ENV['REDIS_USERNAME'] ?? null,
            'password' => $_ENV['REDIS_PASSWORD'] ?? null,
            'port' => $_ENV['REDIS_PORT'] ?? '6379',
            'database' => $_ENV['REDIS_DB'] ?? '0',
        ],
    ],
];