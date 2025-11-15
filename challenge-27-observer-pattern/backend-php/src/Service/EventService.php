<?php

namespace App\Service;

use App\Subject\NewsAgency;
use App\Subject\UserEventManager;

/**
 * Event service to manage subjects and observers
 * 
 * Provides a central service for managing the observer pattern implementation
 */
class EventService
{
    private NewsAgency $newsAgency;
    private UserEventManager $userEventManager;

    /**
     * Constructor
     *
     * @param NewsAgency $newsAgency The news agency subject
     * @param UserEventManager $userEventManager The user event manager subject
     */
    public function __construct(NewsAgency $newsAgency, UserEventManager $userEventManager)
    {
        $this->newsAgency = $newsAgency;
        $this->userEventManager = $userEventManager;
    }

    /**
     * Get the news agency subject
     *
     * @return NewsAgency The news agency
     */
    public function getNewsAgency(): NewsAgency
    {
        return $this->newsAgency;
    }

    /**
     * Get the user event manager subject
     *
     * @return UserEventManager The user event manager
     */
    public function getUserEventManager(): UserEventManager
    {
        return $this->userEventManager;
    }

    /**
     * Publish news through the news agency
     *
     * @param string $title The news title
     * @param string $content The news content
     * @return void
     */
    public function publishNews(string $title, string $content): void
    {
        $this->newsAgency->publishNews($title, $content);
    }

    /**
     * Record a user login event
     *
     * @param string $userId The user ID
     * @param string $ipAddress The IP address of the user
     * @return void
     */
    public function recordUserLogin(string $userId, string $ipAddress): void
    {
        $this->userEventManager->recordUserLogin($userId, $ipAddress);
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
        $this->userEventManager->recordUserRegistration($userId, $email);
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
        $this->userEventManager->recordUserAction($userId, $action, $data);
    }
}