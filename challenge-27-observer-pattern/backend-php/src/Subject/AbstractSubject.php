<?php

namespace App\Subject;

use App\Observer\ObserverInterface;

/**
 * Abstract base class for subjects
 * 
 * Provides common functionality for all subjects
 */
abstract class AbstractSubject implements SubjectInterface
{
    /**
     * @var ObserverInterface[]
     */
    protected array $observers = [];

    /**
     * Attach an observer to the subject
     *
     * @param ObserverInterface $observer The observer to attach
     * @return void
     */
    public function attach(ObserverInterface $observer): void
    {
        $this->observers[] = $observer;
    }

    /**
     * Detach an observer from the subject
     *
     * @param ObserverInterface $observer The observer to detach
     * @return void
     */
    public function detach(ObserverInterface $observer): void
    {
        $key = array_search($observer, $this->observers, true);
        if ($key !== false) {
            unset($this->observers[$key]);
            // Re-index array to ensure sequential keys
            $this->observers = array_values($this->observers);
        }
    }

    /**
     * Notify all observers about an event
     *
     * @param string $event The event that occurred
     * @param mixed $data Additional data related to the event
     * @return void
     */
    public function notify(string $event, $data = null): void
    {
        foreach ($this->observers as $observer) {
            $observer->update($this, $event, $data);
        }
    }

    /**
     * Get the list of attached observers
     *
     * @return ObserverInterface[] The list of observers
     */
    public function getObservers(): array
    {
        return $this->observers;
    }
}