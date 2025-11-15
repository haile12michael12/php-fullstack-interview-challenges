<?php

namespace App\Observer;

/**
 * Observer interface for the Observer design pattern
 * 
 * Defines the contract for objects that should be notified of changes in a subject
 */
interface ObserverInterface
{
    /**
     * Receive update from subject
     *
     * @param mixed $subject The subject that is being observed
     * @param string $event The event that occurred
     * @param mixed $data Additional data related to the event
     * @return void
     */
    public function update($subject, string $event, $data = null): void;
}