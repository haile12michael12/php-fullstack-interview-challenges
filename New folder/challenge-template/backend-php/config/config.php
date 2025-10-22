<?php

return [
    'app' => [
        'name' => 'PHP Challenge',
        'debug' => true,
        'environment' => 'development',
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
    ],
    
    'auth' => [
        'secret_key' => $_ENV['JWT_SECRET'] ?? 'change-me-in-production',
        'token_lifetime' => 3600, // 1 hour
        'refresh_token_lifetime' => 604800, // 7 days
    ],
    
    'storage' => [
        'path' => __DIR__ . '/../storage',
    ],
    
    'logging' => [
        'path' => __DIR__ . '/../logs',
        'level' => 'debug',
    ],
    
    'routes' => [
        '/api/users' => [
            'GET' => 'App\\Controller\\ApiController@getUsers',
            'POST' => 'App\\Controller\\ApiController@createUser',
        ],
        '/api/users/{id}' => [
            'GET' => 'App\\Controller\\ApiController@getUser',
            'PUT' => 'App\\Controller\\ApiController@updateUser',
            'DELETE' => 'App\\Controller\\ApiController@deleteUser',
        ],
        '/api/auth/login' => [
            'POST' => 'App\\Controller\\AuthController@login',
        ],
        '/api/auth/refresh' => [
            'POST' => 'App\\Controller\\AuthController@refresh',
        ],
    ],
];