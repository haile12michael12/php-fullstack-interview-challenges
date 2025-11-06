<?php

namespace Challenge04\Upload;

use Challenge04\Contracts\FileValidatorInterface;

class FileValidator implements FileValidatorInterface
{
    private array $errors = [];

    public function validateFileSize(string $filePath, int $maxSize): bool
    {
        $this->errors = [];
        
        if (!file_exists($filePath)) {
            $this->errors[] = 'File does not exist';
            return false;
        }
        
        $fileSize = filesize($filePath);
        if ($fileSize > $maxSize) {
            $this->errors[] = 'File size exceeds maximum allowed size';
            return false;
        }
        
        return true;
    }

    public function validateFileType(string $filePath, array $allowedTypes): bool
    {
        if (!file_exists($filePath)) {
            $this->errors[] = 'File does not exist';
            return false;
        }
        
        $fileType = mime_content_type($filePath);
        if (!in_array($fileType, $allowedTypes)) {
            $this->errors[] = 'File type is not allowed';
            return false;
        }
        
        return true;
    }

    public function validateFileContent(string $filePath): bool
    {
        if (!file_exists($filePath)) {
            $this->errors[] = 'File does not exist';
            return false;
        }
        
        // Basic content validation - check for null bytes which could indicate malicious content
        $content = file_get_contents($filePath);
        if (strpos($content, "\0") !== false) {
            $this->errors[] = 'File contains null bytes which may indicate malicious content';
            return false;
        }
        
        return true;
    }

    public function getValidationErrors(): array
    {
        return $this->errors;
    }
}