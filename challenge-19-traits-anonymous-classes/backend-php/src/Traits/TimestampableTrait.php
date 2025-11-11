<?php

namespace App\Traits;

trait TimestampableTrait
{
    protected ?\DateTime $createdAt = null;
    protected ?\DateTime $updatedAt = null;

    public function setCreatedAt(?\DateTime $createdAt = null): void
    {
        $this->createdAt = $createdAt ?? new \DateTime();
    }

    public function setUpdatedAt(?\DateTime $updatedAt = null): void
    {
        $this->updatedAt = $updatedAt ?? new \DateTime();
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function updateTimestamps(): void
    {
        if ($this->createdAt === null) {
            $this->setCreatedAt();
        }
        $this->setUpdatedAt();
    }

    public function getCreatedAtAgo(): string
    {
        if ($this->createdAt === null) {
            return 'Unknown';
        }
        
        $now = new \DateTime();
        $diff = $now->diff($this->createdAt);
        
        if ($diff->y > 0) {
            return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
        }
        
        if ($diff->m > 0) {
            return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
        }
        
        if ($diff->d > 0) {
            return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
        }
        
        if ($diff->h > 0) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
        }
        
        if ($diff->i > 0) {
            return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
        }
        
        return 'Just now';
    }
}