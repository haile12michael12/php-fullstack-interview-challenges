<?php

namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\WebSocket\ConnectionManager;
use App\WebSocket\MessageRouter;
use App\Dashboard\MetricsCollector;
use App\Exception\WebSocketException;

class WebSocketServer implements MessageComponentInterface
{
    private $connectionManager;
    private $messageRouter;
    private $metricsCollector;
    
    public function __construct()
    {
        $this->connectionManager = new ConnectionManager();
        $this->messageRouter = new MessageRouter($this->connectionManager);
        $this->metricsCollector = new MetricsCollector();
    }
    
    /**
     * Handle new WebSocket connection
     *
     * @param ConnectionInterface $conn
     * @return void
     */
    public function onOpen(ConnectionInterface $conn)
    {
        try {
            // Store connection
            $this->connectionManager->addConnection($conn);
            
            // Send connection acknowledgment
            $response = [
                'type' => 'connection_ack',
                'data' => [
                    'message' => 'Connection established successfully',
                    'timestamp' => date('c')
                ]
            ];
            
            $conn->send(json_encode($response));
            
            echo "New connection established: {$conn->resourceId}\n";
        } catch (\Exception $e) {
            echo "Error establishing connection: " . $e->getMessage() . "\n";
            $conn->close();
        }
    }
    
    /**
     * Handle incoming message
     *
     * @param ConnectionInterface $from
     * @param string $msg
     * @return void
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        try {
            $data = json_decode($msg, true);
            
            if (!$data) {
                throw new WebSocketException('Invalid JSON message received');
            }
            
            // Route message based on type
            $response = $this->messageRouter->route($from, $data);
            
            if ($response) {
                $from->send(json_encode($response));
            }
        } catch (WebSocketException $e) {
            $errorResponse = [
                'type' => 'error',
                'data' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode()
                ]
            ];
            
            $from->send(json_encode($errorResponse));
        } catch (\Exception $e) {
            $errorResponse = [
                'type' => 'error',
                'data' => [
                    'message' => 'Internal server error',
                    'code' => 500
                ]
            ];
            
            $from->send(json_encode($errorResponse));
            echo "Error processing message: " . $e->getMessage() . "\n";
        }
    }
    
    /**
     * Handle connection close
     *
     * @param ConnectionInterface $conn
     * @return void
     */
    public function onClose(ConnectionInterface $conn)
    {
        // Remove connection from manager
        $this->connectionManager->removeConnection($conn);
        
        echo "Connection {$conn->resourceId} has disconnected\n";
    }
    
    /**
     * Handle connection error
     *
     * @param ConnectionInterface $conn
     * @param \Exception $e
     * @return void
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        
        $errorResponse = [
            'type' => 'error',
            'data' => [
                'message' => 'Connection error occurred',
                'code' => 500
            ]
        ];
        
        $conn->send(json_encode($errorResponse));
        $conn->close();
    }
    
    /**
     * Broadcast message to all connected clients
     *
     * @param array $message
     * @return void
     */
    public function broadcast(array $message)
    {
        $this->connectionManager->broadcast($message);
    }
    
    /**
     * Get connection manager
     *
     * @return ConnectionManager
     */
    public function getConnectionManager(): ConnectionManager
    {
        return $this->connectionManager;
    }
    
    /**
     * Get metrics collector
     *
     * @return MetricsCollector
     */
    public function getMetricsCollector(): MetricsCollector
    {
        return $this->metricsCollector;
    }
}