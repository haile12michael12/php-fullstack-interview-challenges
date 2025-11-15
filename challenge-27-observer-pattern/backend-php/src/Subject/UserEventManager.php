<?php

namespace App\Subject;

/**
 * User event manager subject implementation
 * 
 * Manages user-related events and notifies registered observers
 */
class UserEventManager extends AbstractSubject
{
    private array $events = [];

    /**
     * Record a user login event
     *
     * @param string $userId The user ID
     * @param string $ipAddress The IP address of the user
     * @return void
     */
    public function recordUserLogin(string $userId, string $ipAddress): void
    {
        $event = [
            'type' => 'user.login',
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'timestamp' => new \DateTimeImmutable()
        ];

        $this->events[] = $event;

        // Notify all observers about the login event
        $this->notify('user.login', $event);
    }

    /**
     * Record a user registration event
     *
     * @param string $userId The user ID
     * @param string $email The user email
     * @return void
     */
    public function recordUserRegistration(string $userId, string $email): void
    {
        $event = [
            'type' => 'user.registration',
            'user_id' => $userId,
            'email' => $email,
            'timestamp' => new \DateTimeImmutable()
        ];

        $this->events[] = $event;

        // Notify all observers about the registration event
        $this->notify('user.registration', $event);
    }

    /**
     * Record a user action event
     *
     * @param string $userId The user ID
     * @param string $action The action performed
     * @param array $data Additional data about the action
     * @return void
     */
    public function recordUserAction(string $userId, string $action, array $data = []): void
    {
        $event = [
            'type' => 'user.action',
            'user_id' => $userId,
            'action' => $action,
            'data' => $data,
            'timestamp' => new \DateTimeImmutable()
        ];

        $this->events[] = $event;

        // Notify all observers about the user action
        $this->notify('user.action', $event);
    }

    /**
     * Get all recorded events
     *
     * @return array The list of events
     */
    public function getEvents(): array
    {
        return $this->events;
    }
}