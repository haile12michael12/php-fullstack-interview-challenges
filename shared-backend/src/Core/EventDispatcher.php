<?php

declare(strict_types=1);

namespace SharedBackend\Core;

use Symfony\Component\EventDispatcher\EventDispatcher as SymfonyEventDispatcher;
use Symfony\Component\EventDispatcher\Event;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * Advanced event dispatcher with middleware and async processing capabilities
 */
class EventDispatcher implements EventDispatcherInterface
{
    private SymfonyEventDispatcher $dispatcher;
    private Logger $logger;
    private array $middleware = [];

    public function __construct(Logger $logger)
    {
        $this->dispatcher = new SymfonyEventDispatcher();
        $this->logger = $logger;
    }

    public function dispatch(object $event, string $eventName = null): object
    {
        $eventName = $eventName ?? get_class($event);
        
        $this->logger->debug('Event dispatched', [
            'event' => $eventName,
            'class' => get_class($event)
        ]);

        // Apply middleware
        foreach ($this->middleware as $middleware) {
            $middleware($event, $eventName);
            
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                $this->logger->debug('Event propagation stopped by middleware', [
                    'event' => $eventName
                ]);
                return $event;
            }
        }

        try {
            $result = $this->dispatcher->dispatch($event, $eventName);
            
            $this->logger->debug('Event processing completed', [
                'event' => $eventName
            ]);
            
            return $result;
        } catch (\Exception $e) {
            $this->logger->error('Event processing failed', [
                'event' => $eventName,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function addListener(string $eventName, callable $listener, int $priority = 0): void
    {
        $this->dispatcher->addListener($eventName, $listener, $priority);
        
        $this->logger->debug('Event listener added', [
            'event' => $eventName,
            'priority' => $priority
        ]);
    }

    public function removeListener(string $eventName, callable $listener): void
    {
        $this->dispatcher->removeListener($eventName, $listener);
        
        $this->logger->debug('Event listener removed', [
            'event' => $eventName
        ]);
    }

    public function addSubscriber($subscriber): void
    {
        $this->dispatcher->addSubscriber($subscriber);
        
        $this->logger->debug('Event subscriber added', [
            'subscriber' => get_class($subscriber)
        ]);
    }

    public function removeSubscriber($subscriber): void
    {
        $this->dispatcher->removeSubscriber($subscriber);
        
        $this->logger->debug('Event subscriber removed', [
            'subscriber' => get_class($subscriber)
        ]);
    }

    public function addMiddleware(callable $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    public function getListeners(string $eventName = null): array
    {
        return $this->dispatcher->getListeners($eventName);
    }

    public function hasListeners(string $eventName = null): bool
    {
        return $this->dispatcher->hasListeners($eventName);
    }
}
