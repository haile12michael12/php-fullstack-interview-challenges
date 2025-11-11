<?php

return [
    'name' => 'Late Static Binding Challenge',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => $_ENV['APP_DEBUG'] ?? false,
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'timezone' => 'UTC',
    'locale' => 'en',
    'key' => $_ENV['APP_KEY'] ?? '',
    
    'providers' => [
        // Service providers would be listed here
    ],
    
    'aliases' => [
        'App' => App\Facades\App::class,
        'Config' => App\Facades\Config::class,
        'DB' => App\Facades\DB::class,
        'Log' => App\Facades\Log::class,
    ],
];