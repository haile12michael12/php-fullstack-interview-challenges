<?php

namespace Challenge03\Notification;

use Challenge03\LongPolling\MessageQueue;

class NotificationService
{
    private MessageQueue $messageQueue;

    public function __construct(MessageQueue $messageQueue)
    {
        $this->messageQueue = $messageQueue;
    }

    public function sendNotification(array $notificationData): bool
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

    public function broadcastNotification(array $notificationData, array $targetCategories = []): int
    {
        // For simplicity, we'll add the message to the queue
        // In a real implementation, you might want to handle broadcasting differently
        $this->sendNotification($notificationData);
        return 1; // Number of messages sent
    }

    public function sendToClient(string $clientId, array $notificationData): bool
    {
        // Add client-specific metadata
        $notificationData['metadata']['target_client'] = $clientId;
        return $this->sendNotification($notificationData);
    }
}