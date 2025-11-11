<?php

return [
    // Service providers
    'providers' => [
        App\Services\Providers\LoggingServiceProvider::class,
        App\Services\Providers\MailServiceProvider::class,
        App\Services\Providers\DatabaseServiceProvider::class,
    ],
    
    // Service bindings
    'bindings' => [
        // Interface to implementation bindings
        App\Services\Interfaces\LoggerInterface::class => App\Services\Implementations\FileLogger::class,
        App\Services\Interfaces\MailerInterface::class => App\Services\Implementations\SmtpMailer::class,
    ],
    
    // Service parameters
    'parameters' => [
        'logger.file' => 'app.log',
        'logger.date_format' => 'Y-m-d H:i:s',
        'mailer.host' => 'smtp.example.com',
        'mailer.port' => 587,
        'mailer.username' => 'username',
        'mailer.password' => 'password',
        'database.host' => 'localhost',
        'database.name' => 'reflection_api',
        'database.username' => 'root',
        'database.password' => '',
    ],
];