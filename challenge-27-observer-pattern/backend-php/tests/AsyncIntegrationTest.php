<?php

namespace Tests;

use App\Messenger\Message\PublishNewsMessage;
use App\Messenger\Handler\PublishNewsHandler;
use App\Observer\NewsChannel;
use App\Service\EventService;
use App\Subject\NewsAgency;
use App\Subject\UserEventManager;
use PHPUnit\Framework\TestCase;

class AsyncIntegrationTest extends TestCase
{
    public function testAsyncNewsPublishing(): void
    {
        // Create subjects
        $newsAgency = new NewsAgency('Test News Agency');
        $userEventManager = new UserEventManager();
        
        // Create service
        $eventService = new EventService($newsAgency, $userEventManager);
        
        // Create observer
        $bbc = new NewsChannel('BBC');
        
        // Attach observer
        $newsAgency->attach($bbc);
        
        // Create message and handler
        $message = new PublishNewsMessage('Async News', 'This news was published asynchronously.');
        $handler = new PublishNewsHandler($eventService);
        
        // Handle the message
        $handler($message);
        
        // Assert that the news was published and the observer was notified
        $this->assertCount(1, $bbc->getReceivedNews());
        $receivedNews = $bbc->getReceivedNews();
        $this->assertEquals('Async News', $receivedNews[0]['title']);
        $this->assertEquals('This news was published asynchronously.', $receivedNews[0]['content']);
    }
}