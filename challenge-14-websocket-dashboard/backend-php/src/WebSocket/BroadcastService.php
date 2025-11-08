<?php

namespace App\WebSocket;

use App\WebSocket\ConnectionManager;
use App\Dashboard\MetricsCollector;

class BroadcastService
{
    private $connectionManager;
    private $metricsCollector;
    private $loop;
    
    public function __construct(ConnectionManager $connectionManager)
    {
        $this->connectionManager = $connectionManager;
        $this->metricsCollector = new MetricsCollector();
    }
    
    /**
     * Start broadcasting metrics to subscribed clients
     *
     * @param \React\EventLoop\LoopInterface $loop
     * @return void
     */
    public function startMetricsBroadcast($loop)
    {
        // Broadcast metrics every second
        $loop->addPeriodicTimer(1, function () {
            $this->broadcastMetrics();
        });
    }
    
    /**
     * Broadcast current metrics to all subscribed clients
     *
     * @return void
     */
    public function broadcastMetrics()
    {
        $metrics = $this->metricsCollector->collect();
        
        $message = [
            'type' => 'metrics_update',
            'data' => $metrics,
            'timestamp' => date('c')
        ];
        
        // Send to all connections that are subscribed to metrics
        $connections = $this->connectionManager->getConnections();
        
        foreach ($connections as $conn) {
            $connData = $this->connectionManager->getConnectionData($conn);
            
            // Check if connection is subscribed to metrics
            if ($connData && ($connData['subscribed_to_metrics'] ?? false)) {
                $this->connectionManager->sendTo($conn, $message);
            }
        }
    }
    
    /**
     * Broadcast notification to all clients
     *
     * @param string $message
     * @param string|null $type
     * @return void
     */
    public function broadcastNotification(string $message, ?string $type = 'notification')
    {
        $notification = [
            'type' => $type,
            'data' => [
                'message' => $message,
                'timestamp' => date('c')
            ]
        ];
        
        $this->connectionManager->broadcast($notification);
    }
    
    /**
     * Broadcast message to specific user
     *
     * @param string $userId
     * @param array $message
     * @return void
     */
    public function broadcastToUser(string $userId, array $message)
    {
        $connections = $this->connectionManager->getConnectionsByUserId($userId);
        
        foreach ($connections as $conn) {
            $this->connectionManager->sendTo($conn, $message);
        }
    }
    
    /**
     * Send direct message to a specific connection
     *
     * @param \Ratchet\ConnectionInterface $conn
     * @param array $message
     * @return void
     */
    public function sendDirectMessage($conn, array $message)
    {
        $this->connectionManager->sendTo($conn, $message);
    }
}