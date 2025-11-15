<?php

namespace App\Observer;

/**
 * Abstract base class for observers
 * 
 * Provides common functionality for all observers
 */
abstract class AbstractObserver implements ObserverInterface
{
    protected string $name;
    protected array $events = [];

    /**
     * Constructor
     *
     * @param string $name The name of the observer
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of the observer
     *
     * @return string The observer name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the events this observer is interested in
     *
     * @param array $events List of event names
     * @return self
     */
    public function setEvents(array $events): self
    {
        $this->events = $events;
        return $this;
    }

    /**
     * Check if this observer is interested in a specific event
     *
     * @param string $event The event name
     * @return bool True if the observer is interested in the event, false otherwise
     */
    public function isInterestedIn(string $event): bool
    {
        // If no events are specified, observer is interested in all events
        if (empty($this->events)) {
            return true;
        }

        return in_array($event, $this->events);
    }

    /**
     * Receive update from subject
     *
     * @param mixed $subject The subject that is being observed
     * @param string $event The event that occurred
     * @param mixed $data Additional data related to the event
     * @return void
     */
    public function update($subject, string $event, $data = null): void
    {
        // Only process events this observer is interested in
        if ($this->isInterestedIn($event)) {
            $this->handleUpdate($subject, $event, $data);
        }
    }

    /**
     * Handle the update from the subject
     *
     * This method should be implemented by concrete observers
     *
     * @param mixed $subject The subject that is being observed
     * @param string $event The event that occurred
     * @param mixed $data Additional data related to the event
     * @return void
     */
    abstract protected function handleUpdate($subject, string $event, $data = null): void;
}