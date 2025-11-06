<?php

namespace Challenge04\Storage;

use Challenge04\Contracts\FileStorageInterface;

class LocalFileStorage implements FileStorageInterface
{
    private string $storagePath;
    private string $quarantinePath;

    public function __construct(string $storagePath = '/tmp/storage', string $quarantinePath = '/tmp/quarantine')
    {
        $this->storagePath = $storagePath;
        $this->quarantinePath = $quarantinePath;
        
        // Create directories if they don't exist
        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }
        
        if (!is_dir($this->quarantinePath)) {
            mkdir($this->quarantinePath, 0755, true);
        }
    }

    public function storeFile(string $sourcePath, string $destinationName): string
    {
        if (!file_exists($sourcePath)) {
            throw new \Exception('Source file does not exist');
        }
        
        $destinationPath = $this->storagePath . '/' . $destinationName;
        
        if (!copy($sourcePath, $destinationPath)) {
            throw new \Exception('Failed to store file');
        }
        
        return $destinationPath;
    }

    public function retrieveFile(string $fileId): string
    {
        $filePath = $this->storagePath . '/' . $fileId;
        
        if (!file_exists($filePath)) {
            throw new \Exception('File not found');
        }
        
        return $filePath;
    }

    public function deleteFile(string $fileId): bool
    {
        $filePath = $this->storagePath . '/' . $fileId;
        
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        
        return false;
    }

    public function getFileMetadata(string $fileId): array
    {
        $filePath = $this->storagePath . '/' . $fileId;
        
        if (!file_exists($filePath)) {
            return [];
        }
        
        return [
            'id' => $fileId,
            'size' => filesize($filePath),
            'modified_time' => filemtime($filePath),
            'created_time' => filectime($filePath),
            'permissions' => fileperms($filePath)
        ];
    }

    public function quarantineFile(string $fileId): bool
    {
        $sourcePath = $this->storagePath . '/' . $fileId;
        $destinationPath = $this->quarantinePath . '/' . $fileId;
        
        if (file_exists($sourcePath)) {
            return rename($sourcePath, $destinationPath);
        }
        
        return false;
    }
}