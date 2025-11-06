<?php

namespace App\Core\Traits;

trait FileValidationTrait
{
    protected function isValidImage(string $filePath): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType = mime_content_type($filePath);
        
        return in_array($fileType, $allowedTypes);
    }

    protected function isValidFileSize(string $filePath, int $maxSize = 5242880): bool // 5MB default
    {
        if (!file_exists($filePath)) {
            return false;
        }

        return filesize($filePath) <= $maxSize;
    }
}