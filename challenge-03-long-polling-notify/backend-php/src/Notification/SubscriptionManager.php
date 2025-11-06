<?php

namespace Challenge03\Notification;

class SubscriptionManager
{
    private array $subscriptions = [];

    public function subscribe(string $clientId, string $category): bool
    {
        if (!isset($this->subscriptions[$category])) {
            $this->subscriptions[$category] = [];
        }
        
        if (!in_array($clientId, $this->subscriptions[$category])) {
            $this->subscriptions[$category][] = $clientId;
        }
        
        return true;
    }

    public function unsubscribe(string $clientId, string $category): bool
    {
        if (!isset($this->subscriptions[$category])) {
            return false;
        }
        
        $key = array_search($clientId, $this->subscriptions[$category]);
        if ($key !== false) {
            unset($this->subscriptions[$category][$key]);
            // Re-index array
            $this->subscriptions[$category] = array_values($this->subscriptions[$category]);
            return true;
        }
        
        return false;
    }

    public function getSubscribers(string $category): array
    {
        return $this->subscriptions[$category] ?? [];
    }

    public function getCategoriesForClient(string $clientId): array
    {
        $categories = [];
        foreach ($this->subscriptions as $category => $subscribers) {
            if (in_array($clientId, $subscribers)) {
                $categories[] = $category;
            }
        }
        return $categories;
    }

    public function isSubscribed(string $clientId, string $category): bool
    {
        return isset($this->subscriptions[$category]) && 
               in_array($clientId, $this->subscriptions[$category]);
    }

    public function unsubscribeFromAll(string $clientId): int
    {
        $count = 0;
        foreach ($this->subscriptions as $category => $subscribers) {
            $key = array_search($clientId, $subscribers);
            if ($key !== false) {
                unset($this->subscriptions[$category][$key]);
                $this->subscriptions[$category] = array_values($this->subscriptions[$category]);
                $count++;
            }
        }
        return $count;
    }
}