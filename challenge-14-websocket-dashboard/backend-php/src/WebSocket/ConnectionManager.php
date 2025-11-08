<?php

namespace App\WebSocket;

use Ratchet\ConnectionInterface;
use App\Exception\ConnectionException;

class ConnectionManager
{
    private $connections;
    private $connectionData;
    
    public function __construct()
    {
        $this->connections = new \SplObjectStorage();
        $this->connectionData = [];
    }
    
    /**
     * Add a new connection
     *
     * @param ConnectionInterface $conn
     * @param array $data Optional connection metadata
     * @return void
     */
    public function addConnection(ConnectionInterface $conn, array $data = [])
    {
        $this->connections->attach($conn);
        $this->connectionData[$conn->resourceId] = array_merge([
            'connected_at' => time(),
            'last_activity' => time(),
            'authenticated' => false,
            'user_id' => null
        ], $data);
    }
    
    /**
     * Remove a connection
     *
     * @param ConnectionInterface $conn
     * @return bool
     */
    public function removeConnection(ConnectionInterface $conn): bool
    {
        if ($this->connections->contains($conn)) {
            $this->connections->detach($conn);
            unset($this->connectionData[$conn->resourceId]);
            return true;
        }
        
        return false;
    }
    
    /**
     * Get connection count
     *
     * @return int
     */
    public function getConnectionCount(): int
    {
        return $this->connections->count();
    }
    
    /**
     * Get all connections
     *
     * @return \SplObjectStorage
     */
    public function getConnections(): \SplObjectStorage
    {
        return $this->connections;
    }
    
    /**
     * Get connection data
     *
     * @param ConnectionInterface $conn
     * @return array|null
     */
    public function getConnectionData(ConnectionInterface $conn): ?array
    {
        return $this->connectionData[$conn->resourceId] ?? null;
    }
    
    /**
     * Update connection data
     *
     * @param ConnectionInterface $conn
     * @param array $data
     * @return void
     */
    public function updateConnectionData(ConnectionInterface $conn, array $data)
    {
        if (isset($this->connectionData[$conn->resourceId])) {
            $this->connectionData[$conn->resourceId] = array_merge(
                $this->connectionData[$conn->resourceId],
                $data
            );
        }
    }
    
    /**
     * Broadcast message to all connections
     *
     * @param array $message
     * @return void
     */
    public function broadcast(array $message)
    {
        $messageJson = json_encode($message);
        
        foreach ($this->connections as $conn) {
            try {
                $conn->send($messageJson);
            } catch (\Exception $e) {
                // Remove broken connections
                $this->removeConnection($conn);
                echo "Removed broken connection: " . $e->getMessage() . "\n";
            }
        }
    }
    
    /**
     * Send message to specific connection
     *
     * @param ConnectionInterface $conn
     * @param array $message
     * @return bool
     */
    public function sendTo(ConnectionInterface $conn, array $message): bool
    {
        try {
            $conn->send(json_encode($message));
            return true;
        } catch (\Exception $e) {
            $this->removeConnection($conn);
            return false;
        }
    }
    
    /**
     * Get connections by user ID
     *
     * @param string $userId
     * @return array
     */
    public function getConnectionsByUserId(string $userId): array
    {
        $userConnections = [];
        
        foreach ($this->connections as $conn) {
            $data = $this->getConnectionData($conn);
            if ($data && isset($data['user_id']) && $data['user_id'] === $userId) {
                $userConnections[] = $conn;
            }
        }
        
        return $userConnections;
    }
    
    /**
     * Close all connections
     *
     * @return void
     */
    public function closeAll()
    {
        foreach ($this->connections as $conn) {
            try {
                $conn->close();
            } catch (\Exception $e) {
                // Ignore errors when closing
            }
        }
        
        $this->connections = new \SplObjectStorage();
        $this->connectionData = [];
    }
}