<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Boot\Container;
use App\Observer\EmailNotifier;
use App\Observer\NewsChannel;
use App\Observer\SmsNotifier;
use App\Service\EventService;

// Build the container
$container = Container::build();

// Get the event service
$eventService = $container->get(EventService::class);

// Get the subjects
$newsAgency = $eventService->getNewsAgency();
$userEventManager = $eventService->getUserEventManager();

// Create and attach observers
$bbc = $container->get(NewsChannel::class . '.bbc');
$cnn = $container->get(NewsChannel::class . '.cnn');
$emailNotifier = $container->get(EmailNotifier::class);
$smsNotifier = $container->get(SmsNotifier::class);

$newsAgency->attach($bbc);
$newsAgency->attach($cnn);
$newsAgency->attach($emailNotifier);

$userEventManager->attach($emailNotifier);
$userEventManager->attach($smsNotifier);

// Simple HTTP server implementation
if (php_sapi_name() === 'cli') {
    echo "Starting Observer Pattern demo server...\n";
    echo "Visit http://localhost:8000 to see the demo\n";
    echo "Press Ctrl+C to stop the server\n\n";
    
    // This is a simplified server for demonstration purposes
    // In a real application, you would use a proper web framework
    
    // Simulate some events
    $eventService->publishNews("Server Started", "The observer pattern demo server is now running.");
    $eventService->recordUserRegistration("demo_user", "demo@example.com");
    
    // Keep the script running
    while (true) {
        sleep(10);
        $eventService->publishNews("Heartbeat", "Server is still running...");
    }
}