<?php

return [
    'external_api' => [
        'base_url' => $_ENV['EXTERNAL_API_BASE_URL'] ?? 'https://api.example.com',
        'timeout' => $_ENV['EXTERNAL_API_TIMEOUT'] ?? 30,
        'retry_attempts' => $_ENV['EXTERNAL_API_RETRY_ATTEMPTS'] ?? 3,
        'retry_delay' => $_ENV['EXTERNAL_API_RETRY_DELAY'] ?? 1000,
    ],
    
    'mail' => [
        'driver' => $_ENV['MAIL_MAILER'] ?? 'smtp',
        'host' => $_ENV['MAIL_HOST'] ?? 'smtp.mailgun.org',
        'port' => $_ENV['MAIL_PORT'] ?? 587,
        'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
        'username' => $_ENV['MAIL_USERNAME'] ?? null,
        'password' => $_ENV['MAIL_PASSWORD'] ?? null,
        'timeout' => null,
        'local_domain' => $_ENV['MAIL_EHLO_DOMAIN'] ?? null,
    ],
    
    'ses' => [
        'key' => $_ENV['AWS_ACCESS_KEY_ID'] ?? null,
        'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'] ?? null,
        'region' => $_ENV['AWS_DEFAULT_REGION'] ?? 'us-east-1',
    ],
    
    'slack' => [
        'webhook_url' => $_ENV['SLACK_WEBHOOK_URL'] ?? null,
        'channel' => $_ENV['SLACK_CHANNEL'] ?? '#general',
        'username' => $_ENV['SLACK_USERNAME'] ?? 'ErrorBot',
        'icon' => $_ENV['SLACK_ICON'] ?? ':rotating_light:',
    ],
    
    'error_reporting' => [
        'enabled' => filter_var($_ENV['ERROR_REPORTING_ENABLED'] ?? true, FILTER_VALIDATE_BOOLEAN),
        'notify_on_critical' => filter_var($_ENV['ERROR_NOTIFY_CRITICAL'] ?? true, FILTER_VALIDATE_BOOLEAN),
        'notify_on_warning' => filter_var($_ENV['ERROR_NOTIFY_WARNING'] ?? false, FILTER_VALIDATE_BOOLEAN),
        'log_to_slack' => filter_var($_ENV['ERROR_LOG_TO_SLACK'] ?? false, FILTER_VALIDATE_BOOLEAN),
    ],
];