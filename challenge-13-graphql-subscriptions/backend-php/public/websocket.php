<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Ratchet\App as RatchetApp;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use App\GraphQL\Subscription\SubscriptionManager;
use App\GraphQL\GraphQLService;

class GraphQLWebSocketServer implements MessageComponentInterface
{
    private $subscriptionManager;
    private $graphqlService;
    private $clients;
    
    public function __construct()
    {
        $this->subscriptionManager = SubscriptionManager::getInstance();
        $this->graphqlService = new GraphQLService();
        $this->clients = new \SplObjectStorage;
    }
    
    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }
    
    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo "Received message: {$msg}\n";
        
        try {
            $data = json_decode($msg, true);
            
            if (!$data) {
                $from->send(json_encode([
                    'type' => 'error',
                    'payload' => 'Invalid JSON'
                ]));
                return;
            }
            
            switch ($data['type'] ?? '') {
                case 'connection_init':
                    $from->send(json_encode([
                        'type' => 'connection_ack'
                    ]));
                    break;
                    
                case 'start':
                    $this->handleStart($from, $data);
                    break;
                    
                case 'stop':
                    $this->handleStop($from, $data);
                    break;
                    
                default:
                    $from->send(json_encode([
                        'type' => 'error',
                        'payload' => 'Unknown message type'
                    ]));
            }
        } catch (\Exception $e) {
            $from->send(json_encode([
                'type' => 'error',
                'payload' => $e->getMessage()
            ]));
        }
    }
    
    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }
    
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
    
    private function handleStart(ConnectionInterface $from, array $data)
    {
        $id = $data['id'] ?? uniqid();
        $payload = $data['payload'] ?? [];
        $query = $payload['query'] ?? '';
        $variables = $payload['variables'] ?? null;
        
        // For subscriptions, we would set up the subscription here
        // For now, we'll just send a mock response
        $from->send(json_encode([
            'type' => 'data',
            'id' => $id,
            'payload' => [
                'data' => null
            ]
        ]));
        
        // Send complete message
        $from->send(json_encode([
            'type' => 'complete',
            'id' => $id
        ]));
    }
    
    private function handleStop(ConnectionInterface $from, array $data)
    {
        $id = $data['id'] ?? '';
        // Handle stopping a subscription
        $from->send(json_encode([
            'type' => 'complete',
            'id' => $id
        ]));
    }
}

// Create WebSocket server
$port = $_ENV['WEBSOCKET_PORT'] ?? 8081;
$websocketServer = new GraphQLWebSocketServer();

$app = new RatchetApp('localhost', $port);
$app->route('/graphql', $websocketServer, ['*']);
echo "WebSocket server started on port {$port}\n";
$app->run();