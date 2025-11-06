<?php

namespace Challenge03\Notification;

class Message
{
    public string $id;
    public string $type;
    public string $content;
    public string $category;
    public int $timestamp;
    public array $metadata;
    public bool $delivered;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? uniqid();
        $this->type = $data['type'] ?? 'info';
        $this->content = $data['content'] ?? '';
        $this->category = $data['category'] ?? 'general';
        $this->timestamp = $data['timestamp'] ?? time();
        $this->metadata = $data['metadata'] ?? [];
        $this->delivered = $data['delivered'] ?? false;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'content' => $this->content,
            'category' => $this->category,
            'timestamp' => $this->timestamp,
            'metadata' => $this->metadata,
            'delivered' => $this->delivered
        ];
    }

    public function isExpired(int $maxAgeSeconds): bool
    {
        return (time() - $this->timestamp) > $maxAgeSeconds;
    }

    public function markAsDelivered(): void
    {
        $this->delivered = true;
    }
}