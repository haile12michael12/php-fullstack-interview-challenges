<?php

namespace Challenge03\LongPolling;

class ConnectionManager
{
    private array $connections = [];
    private array $clientCategories = [];

    public function addConnection(string $clientId): bool
    {
        $this->connections[$clientId] = [
            'id' => $clientId,
            'connected_at' => time(),
            'last_activity' => time()
        ];
        
        return true;
    }

    public function removeConnection(string $clientId): bool
    {
        if (isset($this->connections[$clientId])) {
            unset($this->connections[$clientId]);
            if (isset($this->clientCategories[$clientId])) {
                unset($this->clientCategories[$clientId]);
            }
            return true;
        }
        
        return false;
    }

    public function getConnectionCount(): int
    {
        return count($this->connections);
    }

    public function isClientConnected(string $clientId): bool
    {
        return isset($this->connections[$clientId]);
    }

    public function updateLastActivity(string $clientId): bool
    {
        if (isset($this->connections[$clientId])) {
            $this->connections[$clientId]['last_activity'] = time();
            return true;
        }
        
        return false;
    }

    public function setClientCategories(string $clientId, array $categories): bool
    {
        $this->clientCategories[$clientId] = $categories;
        return true;
    }

    public function getClientCategories(string $clientId): array
    {
        return $this->clientCategories[$clientId] ?? [];
    }

    public function getInactiveConnections(int $thresholdSeconds): array
    {
        $inactive = [];
        $currentTime = time();
        
        foreach ($this->connections as $clientId => $connection) {
            if (($currentTime - $connection['last_activity']) > $thresholdSeconds) {
                $inactive[] = $clientId;
            }
        }
        
        return $inactive;
    }
}