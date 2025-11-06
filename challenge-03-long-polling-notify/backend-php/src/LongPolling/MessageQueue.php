<?php

namespace Challenge03\LongPolling;

class MessageQueue
{
    private array $messages = [];
    private array $deliveredMessages = [];

    public function addMessage(array $message): bool
    {
        $message['id'] = $message['id'] ?? uniqid();
        $message['timestamp'] = $message['timestamp'] ?? time();
        $message['delivered'] = false;
        
        $this->messages[$message['id']] = $message;
        return true;
    }

    public function getMessagesForClient(string $clientId, array $categories = []): array
    {
        $messages = [];
        
        foreach ($this->messages as $message) {
            // Skip already delivered messages
            if ($message['delivered']) {
                continue;
            }
            
            // If categories are specified, filter by them
            if (!empty($categories) && !in_array($message['category'], $categories)) {
                continue;
            }
            
            $messages[] = $message;
        }
        
        return $messages;
    }

    public function markAsDelivered(string $messageId): bool
    {
        if (isset($this->messages[$messageId])) {
            $this->messages[$messageId]['delivered'] = true;
            $this->deliveredMessages[] = $messageId;
            return true;
        }
        
        return false;
    }

    public function getPendingMessageCount(): int
    {
        $count = 0;
        foreach ($this->messages as $message) {
            if (!$message['delivered']) {
                $count++;
            }
        }
        return $count;
    }

    public function removeDeliveredMessages(int $olderThanSeconds = 3600): int
    {
        $currentTime = time();
        $removedCount = 0;
        
        foreach ($this->deliveredMessages as $key => $messageId) {
            if (isset($this->messages[$messageId])) {
                $messageTime = $this->messages[$messageId]['timestamp'];
                if (($currentTime - $messageTime) > $olderThanSeconds) {
                    unset($this->messages[$messageId]);
                    unset($this->deliveredMessages[$key]);
                    $removedCount++;
                }
            }
        }
        
        // Re-index array
        $this->deliveredMessages = array_values($this->deliveredMessages);
        
        return $removedCount;
    }

    public function getMessageById(string $messageId): ?array
    {
        return $this->messages[$messageId] ?? null;
    }
}