<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Container\Core\Container;
use App\Container\Core\ReflectionResolver;
use App\Container\Core\ServiceFactory;
use App\Services\Providers\LoggingServiceProvider;
use App\Services\Providers\MailServiceProvider;
use App\Services\Providers\DatabaseServiceProvider;

// Create container
$container = new Container();

// Create resolver
$resolver = new ReflectionResolver($container);

// Create factory
$factory = new ServiceFactory($resolver);

// Register service providers
$container->registerProvider(new LoggingServiceProvider());
$container->registerProvider(new MailServiceProvider());
$container->registerProvider(new DatabaseServiceProvider());

// Example usage
try {
    // Get a logger instance
    $logger = $container->get(App\Services\Interfaces\LoggerInterface::class);
    $logger->info('Reflection API initialized');
    
    // Get a mailer instance
    $mailer = $container->get(App\Services\Interfaces\MailerInterface::class);
    $mailer->setFrom('noreply@example.com');
    
    // Create a service using the factory
    $fileLogger = $factory->create(App\Services\Implementations\FileLogger::class);
    $fileLogger->debug('Service factory working correctly');
    
    // Output success message
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'message' => 'Reflection API initialized successfully',
        'services' => $container->getRegisteredServices()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}