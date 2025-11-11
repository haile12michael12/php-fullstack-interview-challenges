<?php

return [
    'app' => [
        'name' => 'Reflection API Demo',
        'debug' => true,
        'environment' => 'development',
    ],
    
    'database' => [
        'driver' => 'mysql',
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? 3306,
        'database' => $_ENV['DB_NAME'] ?? 'reflection_api',
        'username' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASS'] ?? '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],
    
    'logger' => [
        'file' => $_ENV['LOG_FILE'] ?? 'app.log',
        'level' => $_ENV['LOG_LEVEL'] ?? 'debug',
    ],
    
    'mailer' => [
        'host' => $_ENV['MAIL_HOST'] ?? 'smtp.example.com',
        'port' => $_ENV['MAIL_PORT'] ?? 587,
        'username' => $_ENV['MAIL_USERNAME'] ?? 'username',
        'password' => $_ENV['MAIL_PASSWORD'] ?? 'password',
        'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
    ],
];