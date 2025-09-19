<?php

namespace SharedBackend\Messaging;

class EventDispatcher
{
    private $listeners = [];
    
    /**
     * Register an event listener
     * 
     * @param string $event
     * @param callable $listener
     * @param int $priority
     * @return void
     */
    public function addListener(string $event, callable $listener, int $priority = 0): void
    {
        $this->listeners[$event][$priority][] = $listener;
        
        if (isset($this->listeners[$event]) && count($this->listeners[$event]) > 1) {
            krsort($this->listeners[$event]);
        }
    }
    
    /**
     * Dispatch an event
     * 
     * @param string $event
     * @param array $payload
     * @return array
     */
    public function dispatch(string $event, array $payload = []): array
    {
        $eventObj = new Event($event, $payload);
        
        if (!isset($this->listeners[$event])) {
            return $eventObj->getPayload();
        }
        
        foreach ($this->listeners[$event] as $priority => $listeners) {
            foreach ($listeners as $listener) {
                if ($eventObj->isPropagationStopped()) {
                    break 2;
                }
                
                call_user_func($listener, $eventObj);
            }
        }
        
        return $eventObj->getPayload();
    }
    
    /**
     * Check if an event has listeners
     * 
     * @param string $event
     * @return bool
     */
    public function hasListeners(string $event): bool
    {
        return isset($this->listeners[$event]) && !empty($this->listeners[$event]);
    }
    
    /**
     * Remove all listeners for an event
     * 
     * @param string|null $event
     * @return void
     */
    public function removeListeners(?string $event = null): void
    {
        if ($event !== null) {
            unset($this->listeners[$event]);
        } else {
            $this->listeners = [];
        }
    }
}