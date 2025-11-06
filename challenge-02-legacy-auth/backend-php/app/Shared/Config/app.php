<?php

return [
    'app_name' => 'Challenge 02: Legacy Authentication',
    'version' => '1.0.0',
    'debug' => true,
    
    'jwt' => [
        'secret' => getenv('JWT_SECRET') ?: 'default_jwt_secret',
        'algorithm' => 'HS256',
        'expiration' => 3600, // 1 hour
        'refresh_expiration' => 2592000 // 30 days
    ],
    
    'database' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_PORT') ?: 3306,
        'name' => getenv('DB_NAME') ?: 'challenge_02_auth',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: ''
    ],
    
    'mail' => [
        'driver' => 'smtp',
        'host' => getenv('MAIL_HOST') ?: 'localhost',
        'port' => getenv('MAIL_PORT') ?: 587,
        'username' => getenv('MAIL_USERNAME') ?: '',
        'password' => getenv('MAIL_PASSWORD') ?: '',
        'encryption' => getenv('MAIL_ENCRYPTION') ?: 'tls'
    ],
    
    'security' => [
        'password_cost' => 12,
        'csrf_protection' => true,
        'rate_limiting' => true
    ]
];