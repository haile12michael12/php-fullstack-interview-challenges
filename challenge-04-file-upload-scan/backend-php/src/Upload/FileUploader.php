<?php

namespace Challenge04\Upload;

class FileUploader
{
    private string $uploadDirectory;
    private int $maxFileSize;
    private array $allowedTypes;

    public function __construct(string $uploadDirectory = '/tmp/uploads', int $maxFileSize = 5242880, array $allowedTypes = [])
    {
        $this->uploadDirectory = $uploadDirectory;
        $this->maxFileSize = $maxFileSize;
        $this->allowedTypes = $allowedTypes;
        
        // Create upload directory if it doesn't exist
        if (!is_dir($this->uploadDirectory)) {
            mkdir($this->uploadDirectory, 0755, true);
        }
    }

    public function upload(array $fileData): array
    {
        // Validate file data
        if (!isset($fileData['tmp_name']) || !isset($fileData['name'])) {
            throw new \Exception('Invalid file data');
        }
        
        $tempPath = $fileData['tmp_name'];
        $originalName = $fileData['name'];
        
        // Validate file
        if (!$this->validateFile($tempPath)) {
            throw new \Exception('File validation failed');
        }
        
        // Generate unique filename
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $uniqueName = uniqid() . '.' . $extension;
        $destinationPath = $this->uploadDirectory . '/' . $uniqueName;
        
        // Move file to destination
        if (!move_uploaded_file($tempPath, $destinationPath)) {
            throw new \Exception('Failed to move uploaded file');
        }
        
        return [
            'original_name' => $originalName,
            'stored_name' => $uniqueName,
            'file_path' => $destinationPath,
            'file_size' => filesize($destinationPath),
            'upload_time' => time()
        ];
    }

    public function validateFile(string $filePath): bool
    {
        // Check file size
        if (filesize($filePath) > $this->maxFileSize) {
            return false;
        }
        
        // Check file type if restrictions are set
        if (!empty($this->allowedTypes)) {
            $mimeType = mime_content_type($filePath);
            if (!in_array($mimeType, $this->allowedTypes)) {
                return false;
            }
        }
        
        return true;
    }

    public function setMaxFileSize(int $maxSize): void
    {
        $this->maxFileSize = $maxSize;
    }

    public function setAllowedTypes(array $types): void
    {
        $this->allowedTypes = $types;
    }
}