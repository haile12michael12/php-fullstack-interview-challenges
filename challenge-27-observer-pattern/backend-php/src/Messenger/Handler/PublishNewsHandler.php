<?php

namespace App\Messenger\Handler;

use App\Messenger\Message\PublishNewsMessage;
use App\Service\EventService;

/**
 * Publish news message handler
 * 
 * Handles publishing news messages asynchronously
 */
class PublishNewsHandler
{
    private EventService $eventService;

    /**
     * Constructor
     *
     * @param EventService $eventService The event service
     */
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Handle the publish news message
     *
     * @param PublishNewsMessage $message The message to handle
     * @return void
     */
    public function __invoke(PublishNewsMessage $message): void
    {
        // Publish the news through the event service
        $this->eventService->publishNews($message->getTitle(), $message->getContent());
    }
}