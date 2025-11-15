<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Boot\Container;
use App\Messenger\Message\PublishNewsMessage;

// Build the container
$container = Container::build();

echo "Worker started. Waiting for messages...\n";

// Simulate processing messages
while (true) {
    // In a real implementation, this would listen for messages from a queue
    // For this example, we'll just exit after a short delay
    sleep(1);
    
    // Simulate receiving a message
    $message = new PublishNewsMessage('Worker News', 'This news was processed by a worker.');
    
    // Get the handler and process the message
    $handler = $container->get(\App\Messenger\Handler\PublishNewsHandler::class);
    $handler($message);
    
    echo "Processed message: {$message->getTitle()}\n";
    
    // Exit after processing one message for this example
    break;
}

echo "Worker finished.\n";