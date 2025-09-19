<?php

namespace App\Chat;

use Chat\ChatMessage;

/**
 * MessageStore - Handles persistent storage of chat messages
 */
class MessageStore
{
    private $messages = [];
    private $maxMessages = 100;
    private $storageFile;
    
    public function __construct(string $storageFile = null)
    {
        $this->storageFile = $storageFile ?? __DIR__ . '/../../data/messages.json';
        $this->loadMessages();
    }
    
    /**
     * Add a message to the store
     */
    public function addMessage(ChatMessage $message): void
    {
        $this->messages[] = $this->messageToArray($message);
        
        // Trim if we exceed max messages
        if (count($this->messages) > $this->maxMessages) {
            array_shift($this->messages);
        }
        
        $this->saveMessages();
    }
    
    /**
     * Get messages before a specific timestamp
     */
    public function getMessagesBefore(int $timestamp, int $limit): array
    {
        $filtered = array_filter($this->messages, function($msg) use ($timestamp) {
            return $msg['timestamp'] < $timestamp;
        });
        
        // Sort by timestamp (newest first)
        usort($filtered, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });
        
        return array_slice($filtered, 0, $limit);
    }
    
    /**
     * Get the most recent messages
     */
    public function getRecentMessages(int $limit): array
    {
        $messages = $this->messages;
        
        // Sort by timestamp (newest first)
        usort($messages, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });
        
        return array_slice($messages, 0, $limit);
    }
    
    /**
     * Convert a ChatMessage to an array for storage
     */
    private function messageToArray(ChatMessage $message): array
    {
        return [
            'id' => $message->getId(),
            'user_id' => $message->getUserId(),
            'username' => $message->getUsername(),
            'content' => $message->getContent(),
            'timestamp' => $message->getTimestamp(),
            'type' => $message->getType()
        ];
    }
    
    /**
     * Convert an array to a ChatMessage
     */
    public function arrayToMessage(array $data): ChatMessage
    {
        $message = new ChatMessage();
        $message->setId($data['id']);
        $message->setUserId($data['user_id']);
        $message->setUsername($data['username']);
        $message->setContent($data['content']);
        $message->setTimestamp($data['timestamp']);
        $message->setType($data['type']);
        
        return $message;
    }
    
    /**
     * Load messages from storage
     */
    private function loadMessages(): void
    {
        if (file_exists($this->storageFile)) {
            $data = file_get_contents($this->storageFile);
            $this->messages = json_decode($data, true) ?? [];
        } else {
            // Ensure directory exists
            $dir = dirname($this->storageFile);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $this->messages = [];
        }
    }
    
    /**
     * Save messages to storage
     */
    private function saveMessages(): void
    {
        file_put_contents($this->storageFile, json_encode($this->messages));
    }
}