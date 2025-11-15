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

// Create observers
$bbc = $container->get(NewsChannel::class . '.bbc');
$cnn = $container->get(NewsChannel::class . '.cnn');
$emailNotifier = $container->get(EmailNotifier::class);
$smsNotifier = $container->get(SmsNotifier::class);

// Attach observers to subjects
$newsAgency->attach($bbc);
$newsAgency->attach($cnn);
$newsAgency->attach($emailNotifier);

$userEventManager->attach($emailNotifier);
$userEventManager->attach($smsNotifier);

echo "=== Observer Pattern Demo ===\n\n";

// Publish some news
echo "1. Publishing news articles:\n";
$eventService->publishNews("Breaking News", "This is a breaking news article.");
$eventService->publishNews("Sports Update", "The local team won the championship.");

echo "\n2. Recording user events:\n";
$eventService->recordUserRegistration("user123", "user@example.com");
$eventService->recordUserLogin("user123", "192.168.1.100");
$eventService->recordUserAction("user123", "purchase", ["item" => "book", "price" => 29.99]);

echo "\n=== Demo Complete ===\n";

// Show received news by BBC
echo "\nBBC News Channel received " . count($bbc->getReceivedNews()) . " news articles.\n";

// Show sent emails
echo "Email Notifier sent " . count($emailNotifier->getSentEmails()) . " emails.\n";

// Show sent SMS
echo "SMS Notifier sent " . count($smsNotifier->getSentSms()) . " SMS messages.\n";