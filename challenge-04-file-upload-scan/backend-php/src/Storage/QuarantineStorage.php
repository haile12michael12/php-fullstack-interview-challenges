<?php

namespace Challenge04\Storage;

class QuarantineStorage
{
    private string $quarantinePath;

    public function __construct(string $quarantinePath = '/tmp/quarantine')
    {
        $this->quarantinePath = $quarantinePath;
        
        // Create quarantine directory if it doesn't exist
        if (!is_dir($this->quarantinePath)) {
            mkdir($this->quarantinePath, 0755, true);
        }
    }

    public function moveToQuarantine(string $sourcePath, string $fileName = null): string
    {
        if (!file_exists($sourcePath)) {
            throw new \Exception('Source file does not exist');
        }
        
        $fileName = $fileName ?? basename($sourcePath);
        $quarantineName = date('YmdHis') . '_' . $fileName;
        $destinationPath = $this->quarantinePath . '/' . $quarantineName;
        
        if (!rename($sourcePath, $destinationPath)) {
            throw new \Exception('Failed to move file to quarantine');
        }
        
        return $destinationPath;
    }

    public function getQuarantinedFiles(): array
    {
        $files = [];
        $iterator = new \DirectoryIterator($this->quarantinePath);
        
        foreach ($iterator as $file) {
            if ($file->isFile() && !$file->isDot()) {
                $files[] = [
                    'name' => $file->getFilename(),
                    'size' => $file->getSize(),
                    'modified_time' => $file->getMTime()
                ];
            }
        }
        
        return $files;
    }

    public function deleteQuarantinedFile(string $fileName): bool
    {
        $filePath = $this->quarantinePath . '/' . $fileName;
        
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        
        return false;
    }

    public function getQuarantinePath(): string
    {
        return $this->quarantinePath;
    }
}