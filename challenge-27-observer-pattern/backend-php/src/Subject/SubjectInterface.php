<?php

namespace App\Subject;

use App\Observer\ObserverInterface;

/**
 * Subject interface for the Observer design pattern
 * 
 * Defines the contract for subjects that can be observed
 */
interface SubjectInterface
{
    /**
     * Attach an observer to the subject
     *
     * @param ObserverInterface $observer The observer to attach
     * @return void
     */
    public function attach(ObserverInterface $observer): void;

    /**
     * Detach an observer from the subject
     *
     * @param ObserverInterface $observer The observer to detach
     * @return void
     */
    public function detach(ObserverInterface $observer): void;

    /**
     * Notify all observers about an event
     *
     * @param string $event The event that occurred
     * @param mixed $data Additional data related to the event
     * @return void
     */
    public function notify(string $event, $data = null): void;
}