<?php

namespace App\Observer;

/**
 * News channel observer implementation
 * 
 * Receives news updates from news agencies
 */
class NewsChannel extends AbstractObserver
{
    private array $receivedNews = [];

    /**
     * Handle the update from the subject
     *
     * @param mixed $subject The subject that is being observed
     * @param string $event The event that occurred
     * @param mixed $data Additional data related to the event
     * @return void
     */
    protected function handleUpdate($subject, string $event, $data = null): void
    {
        if ($event === 'news.published') {
            $this->receivedNews[] = $data;
            echo "[NewsChannel: {$this->getName()}] Received news: {$data['title']}\n";
        }
    }

    /**
     * Get all received news
     *
     * @return array The list of received news
     */
    public function getReceivedNews(): array
    {
        return $this->receivedNews;
    }
}