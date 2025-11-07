<?php

namespace App\Services\Messaging;

class NotificationService
{
    private array $notifications = [];

    public function sendNotification(int $userId, string $message, string $type = 'info'): void
    {
        $notification = [
            'id' => uniqid(),
            'user_id' => $userId,
            'message' => $message,
            'type' => $type,
            'created_at' => date('Y-m-d H:i:s'),
            'read' => false
        ];
        
        $this->notifications[] = $notification;
        
        // In a real implementation, you would store this in a database
        // or send it via a messaging system like Redis or RabbitMQ
    }

    public function getNotifications(int $userId): array
    {
        $userNotifications = [];
        foreach ($this->notifications as $notification) {
            if ($notification['user_id'] === $userId && !$notification['read']) {
                $userNotifications[] = $notification;
            }
        }
        
        return $userNotifications;
    }

    public function markAsRead(string $notificationId): bool
    {
        foreach ($this->notifications as &$notification) {
            if ($notification['id'] === $notificationId) {
                $notification['read'] = true;
                return true;
            }
        }
        
        return false;
    }

    public function markAllAsRead(int $userId): int
    {
        $count = 0;
        foreach ($this->notifications as &$notification) {
            if ($notification['user_id'] === $userId && !$notification['read']) {
                $notification['read'] = true;
                $count++;
            }
        }
        
        return $count;
    }

    public function getUnreadCount(int $userId): int
    {
        $count = 0;
        foreach ($this->notifications as $notification) {
            if ($notification['user_id'] === $userId && !$notification['read']) {
                $count++;
            }
        }
        
        return $count;
    }
}