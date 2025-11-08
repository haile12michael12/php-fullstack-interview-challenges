<?php

namespace App\WebSocket;

use Ratchet\ConnectionInterface;
use App\WebSocket\ConnectionManager;
use App\Dashboard\MetricsCollector;

class MessageRouter
{
    private $connectionManager;
    private $metricsCollector;
    
    public function __construct(ConnectionManager $connectionManager)
    {
        $this->connectionManager = $connectionManager;
        $this->metricsCollector = new MetricsCollector();
    }
    
    /**
     * Route message based on type
     *
     * @param ConnectionInterface $conn
     * @param array $message
     * @return array|null
     */
    public function route(ConnectionInterface $conn, array $message): ?array
    {
        $type = $message['type'] ?? '';
        
        switch ($type) {
            case 'ping':
                return $this->handlePing($conn, $message);
                
            case 'subscribe_metrics':
                return $this->handleSubscribeMetrics($conn, $message);
                
            case 'unsubscribe_metrics':
                return $this->handleUnsubscribeMetrics($conn, $message);
                
            case 'get_current_metrics':
                return $this->handleGetCurrentMetrics($conn, $message);
                
            case 'authenticate':
                return $this->handleAuthenticate($conn, $message);
                
            default:
                return $this->handleUnknownMessage($conn, $message);
        }
    }
    
    /**
     * Handle ping message
     *
     * @param ConnectionInterface $conn
     * @param array $message
     * @return array
     */
    private function handlePing(ConnectionInterface $conn, array $message): array
    {
        return [
            'type' => 'pong',
            'data' => [
                'timestamp' => date('c'),
                'correlation_id' => $message['correlation_id'] ?? null
            ]
        ];
    }
    
    /**
     * Handle metrics subscription
     *
     * @param ConnectionInterface $conn
     * @param array $message
     * @return array
     */
    private function handleSubscribeMetrics(ConnectionInterface $conn, array $message): array
    {
        // Update connection data to indicate metrics subscription
        $this->connectionManager->updateConnectionData($conn, [
            'subscribed_to_metrics' => true,
            'metrics_interval' => $message['data']['interval'] ?? 1000
        ]);
        
        return [
            'type' => 'subscription_ack',
            'data' => [
                'message' => 'Subscribed to metrics updates',
                'timestamp' => date('c')
            ]
        ];
    }
    
    /**
     * Handle metrics unsubscription
     *
     * @param ConnectionInterface $conn
     * @param array $message
     * @return array
     */
    private function handleUnsubscribeMetrics(ConnectionInterface $conn, array $message): array
    {
        // Update connection data to indicate metrics unsubscription
        $this->connectionManager->updateConnectionData($conn, [
            'subscribed_to_metrics' => false
        ]);
        
        return [
            'type' => 'unsubscription_ack',
            'data' => [
                'message' => 'Unsubscribed from metrics updates',
                'timestamp' => date('c')
            ]
        ];
    }
    
    /**
     * Handle get current metrics request
     *
     * @param ConnectionInterface $conn
     * @param array $message
     * @return array
     */
    private function handleGetCurrentMetrics(ConnectionInterface $conn, array $message): array
    {
        $metrics = $this->metricsCollector->collect();
        
        return [
            'type' => 'current_metrics',
            'data' => $metrics
        ];
    }
    
    /**
     * Handle authentication request
     *
     * @param ConnectionInterface $conn
     * @param array $message
     * @return array
     */
    private function handleAuthenticate(ConnectionInterface $conn, array $message): array
    {
        $token = $message['data']['token'] ?? '';
        
        // In a real implementation, you would validate the token
        // For this example, we'll accept any non-empty token
        if (!empty($token)) {
            $this->connectionManager->updateConnectionData($conn, [
                'authenticated' => true,
                'user_id' => 'user_' . uniqid()
            ]);
            
            return [
                'type' => 'auth_success',
                'data' => [
                    'message' => 'Authentication successful',
                    'user_id' => 'user_' . uniqid(),
                    'timestamp' => date('c')
                ]
            ];
        } else {
            return [
                'type' => 'auth_error',
                'data' => [
                    'message' => 'Authentication failed',
                    'timestamp' => date('c')
                ]
            ];
        }
    }
    
    /**
     * Handle unknown message type
     *
     * @param ConnectionInterface $conn
     * @param array $message
     * @return array
     */
    private function handleUnknownMessage(ConnectionInterface $conn, array $message): array
    {
        return [
            'type' => 'error',
            'data' => [
                'message' => 'Unknown message type: ' . ($message['type'] ?? 'undefined'),
                'code' => 400
            ]
        ];
    }
}