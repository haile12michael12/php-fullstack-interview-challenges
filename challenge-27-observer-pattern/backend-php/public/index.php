<?php

require_once __DIR__ . '/../vendor/autoload.php';

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

// Handle HTTP requests
$uri = $_SERVER['REQUEST_URI'] ?? '/';

header('Content-Type: application/json');

switch ($uri) {
    case '/':
        echo json_encode([
            'message' => 'Observer Pattern API',
            'endpoints' => [
                'GET /observers' => 'List registered observers',
                'POST /subjects/news' => 'Publish news (notify observers)'
            ]
        ]);
        break;
        
    case '/observers':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            echo json_encode([
                'observers' => [
                    'bbc' => $bbc->getName(),
                    'cnn' => $cnn->getName(),
                    'email' => $emailNotifier->getName(),
                    'sms' => $smsNotifier->getName()
                ]
            ]);
        }
        break;
        
    case '/subjects/news':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $title = $input['title'] ?? 'Untitled';
            $content = $input['content'] ?? '';
            
            $eventService->publishNews($title, $content);
            
            echo json_encode([
                'message' => 'News published successfully',
                'title' => $title
            ]);
        }
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
        break;
}