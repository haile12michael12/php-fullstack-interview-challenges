<?php

namespace Challenge03\LongPolling;

use Challenge03\LongPolling\ConnectionManager;
use Challenge03\LongPolling\MessageQueue;
use Challenge03\Notification\NotificationService;
use Challenge03\Exception\TimeoutException;

class Server
{
    private ConnectionManager $connectionManager;
    private MessageQueue $messageQueue;
    private NotificationService $notificationService;

    public function __construct()
    {
        $this->connectionManager = new ConnectionManager();
        $this->messageQueue = new MessageQueue();
        $this->notificationService = new NotificationService($this->messageQueue);
    }

    public function handlePollRequest(string $clientId, array $categories = []): array
    {
        // Register client connection
        $this->connectionManager->addConnection($clientId);
        
        // Set timeout for long polling
        $timeout = time() + MAX_POLLING_TIME;
        
        // Wait for messages or timeout
        while (time() < $timeout) {
            $messages = $this->messageQueue->getMessagesForClient($clientId, $categories);
            
            if (!empty($messages)) {
                // Mark messages as delivered
                foreach ($messages as $message) {
                    $this->messageQueue->markAsDelivered($message['id']);
                }
                
                return [
                    'status' => 'success',
                    'messages' => $messages
                ];
            }
            
            // Sleep briefly to prevent excessive CPU usage
            usleep(100000); // 0.1 seconds
        }
        
        // Timeout reached
        throw new TimeoutException('No messages received within timeout period');
    }

    public function handleNotification(array $notificationData): bool
    {
        $message = [
            'id' => uniqid(),
            'type' => $notificationData['type'] ?? 'info',
            'content' => $notificationData['content'] ?? '',
            'category' => $notificationData['category'] ?? 'general',
            'timestamp' => time(),
            'metadata' => $notificationData['metadata'] ?? []
        ];
        
        return $this->messageQueue->addMessage($message);
    }

    public function getServerStatus(): array
    {
        return [
            'active_connections' => $this->connectionManager->getConnectionCount(),
            'pending_messages' => $this->messageQueue->getPendingMessageCount(),
            'server_time' => time(),
            'uptime' => $this->getUptime()
        ];
    }

    public function subscribeClient(string $clientId, array $categories): bool
    {
        return $this->connectionManager->setClientCategories($clientId, $categories);
    }

    private function getUptime(): int
    {
        // In a real implementation, you would track server start time
        return time();
    }
}