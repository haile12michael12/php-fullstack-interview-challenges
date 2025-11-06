<?php

namespace App\Infrastructure\Services;

class FileStorageService
{
    private string $storagePath;

    public function __construct(string $storagePath = __DIR__ . '/../../../storage')
    {
        $this->storagePath = $storagePath;
        $this->ensureDirectoryExists($this->storagePath);
    }

    public function save(string $filename, string $content): string
    {
        $filePath = $this->storagePath . '/' . $filename;
        file_put_contents($filePath, $content);
        return $filePath;
    }

    public function load(string $filename): string
    {
        $filePath = $this->storagePath . '/' . $filename;
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("File not found: {$filename}");
        }
        return file_get_contents($filePath);
    }

    public function delete(string $filename): bool
    {
        $filePath = $this->storagePath . '/' . $filename;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    private function ensureDirectoryExists(string $directory): void
    {
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }
}