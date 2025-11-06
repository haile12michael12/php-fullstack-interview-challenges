<?php

return [
    'grpc' => [
        'host' => $_ENV['GRPC_HOST'] ?? 'localhost',
        'port' => $_ENV['GRPC_PORT'] ?? 50051,
        'timeout' => $_ENV['GRPC_TIMEOUT'] ?? 5000,
    ],
    
    'chat' => [
        'max_message_length' => $_ENV['CHAT_MAX_MESSAGE_LENGTH'] ?? 1000,
        'max_room_participants' => $_ENV['CHAT_MAX_ROOM_PARTICIPANTS'] ?? 100,
        'message_history_limit' => $_ENV['CHAT_MESSAGE_HISTORY_LIMIT'] ?? 100,
    ],
    
    'auth' => [
        'jwt_secret' => $_ENV['JWT_SECRET'] ?? 'your_jwt_secret_here',
        'jwt_ttl' => $_ENV['JWT_TTL'] ?? 3600,
        'password_min_length' => $_ENV['PASSWORD_MIN_LENGTH'] ?? 8,
    ],
    
    'mail' => [
        'driver' => $_ENV['MAIL_MAILER'] ?? 'smtp',
        'host' => $_ENV['MAIL_HOST'] ?? 'smtp.mailgun.org',
        'port' => $_ENV['MAIL_PORT'] ?? 587,
        'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
        'username' => $_ENV['MAIL_USERNAME'] ?? null,
        'password' => $_ENV['MAIL_PASSWORD'] ?? null,
        'timeout' => null,
        'auth_mode' => null,
    ],
    
    'notification' => [
        'enabled' => $_ENV['NOTIFICATION_ENABLED'] ?? true,
        'max_notifications_per_user' => $_ENV['MAX_NOTIFICATIONS_PER_USER'] ?? 100,
    ],
];