<?php

namespace App\Storage\Rotation;

class RetentionPolicy
{
    private int $maxFileSize;
    private int $maxAgeDays;

    public function __construct(int $maxFileSize = 10485760, int $maxAgeDays = 30)
    {
        $this->maxFileSize = $maxFileSize; // 10MB default
        $this->maxAgeDays = $maxAgeDays;
    }

    public function shouldRotate(string $file): bool
    {
        if (!file_exists($file)) {
            return false;
        }
        
        return filesize($file) > $this->maxFileSize;
    }

    public function shouldCompress(string $file): bool
    {
        if (!file_exists($file)) {
            return false;
        }
        
        $fileAge = time() - filemtime($file);
        return $fileAge > ($this->maxAgeDays * 24 * 60 * 60);
    }

    public function getMaxFileSize(): int
    {
        return $this->maxFileSize;
    }

    public function setMaxFileSize(int $maxFileSize): void
    {
        $this->maxFileSize = $maxFileSize;
    }

    public function getMaxAgeDays(): int
    {
        return $this->maxAgeDays;
    }

    public function setMaxAgeDays(int $maxAgeDays): void
    {
        $this->maxAgeDays = $maxAgeDays;
    }
}