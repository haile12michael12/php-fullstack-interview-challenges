<?php

return [
    'app' => [
        'name' => 'Challenge 12 - REST API with JWT',
        'debug' => $_ENV['APP_DEBUG'] ?? true,
        'environment' => $_ENV['APP_ENV'] ?? 'development',
    ],
    
    'database' => [
        'driver' => 'mysql',
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? 3306,
        'database' => $_ENV['DB_NAME'] ?? 'challenge_12_db',
        'username' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASS'] ?? '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],
    
    'jwt' => [
        'secret_key' => $_ENV['JWT_SECRET'] ?? 'change-me-in-production',
        'token_ttl' => $_ENV['JWT_TTL'] ?? 3600, // 1 hour
        'refresh_token_ttl' => $_ENV['REFRESH_TOKEN_TTL'] ?? 604800, // 7 days
    ],
    
    'storage' => [
        'path' => __DIR__ . '/../storage',
    ],
    
    'logging' => [
        'path' => __DIR__ . '/../logs',
        'level' => 'debug',
    ],
    
    'routes' => [
        // Auth routes
        '/api/auth/register' => [
            'POST' => 'App\\Controller\\AuthController@register',
        ],
        '/api/auth/login' => [
            'POST' => 'App\\Controller\\AuthController@login',
        ],
        '/api/auth/refresh' => [
            'POST' => 'App\\Controller\\AuthController@refresh',
        ],
        '/api/auth/logout' => [
            'POST' => 'App\\Controller\\AuthController@logout',
        ],
        
        // User routes (protected)
        '/api/users' => [
            'GET' => 'App\\Controller\\UserController@getAll',
            'POST' => 'App\\Controller\\UserController@create',
        ],
        '/api/users/{id}' => [
            'GET' => 'App\\Controller\\UserController@getById',
            'PUT' => 'App\\Controller\\UserController@update',
            'DELETE' => 'App\\Controller\\UserController@delete',
        ],
        
        // Profile routes (protected)
        '/api/profile' => [
            'GET' => 'App\\Controller\\ProfileController@get',
            'PUT' => 'App\\Controller\\ProfileController@update',
        ],
    ],
];