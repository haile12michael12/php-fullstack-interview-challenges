<?php

namespace Challenge04\Storage;

class FileRepository
{
    private array $files = [];

    public function save(array $fileData): string
    {
        $fileId = $fileData['id'] ?? uniqid();
        $fileData['id'] = $fileId;
        $fileData['created_at'] = $fileData['created_at'] ?? time();
        
        $this->files[$fileId] = $fileData;
        
        return $fileId;
    }

    public function findById(string $fileId): ?array
    {
        return $this->files[$fileId] ?? null;
    }

    public function findAll(): array
    {
        return $this->files;
    }

    public function findByStatus(string $status): array
    {
        $results = [];
        foreach ($this->files as $file) {
            if (isset($file['status']) && $file['status'] === $status) {
                $results[] = $file;
            }
        }
        return $results;
    }

    public function update(string $fileId, array $fileData): bool
    {
        if (isset($this->files[$fileId])) {
            $this->files[$fileId] = array_merge($this->files[$fileId], $fileData);
            return true;
        }
        return false;
    }

    public function delete(string $fileId): bool
    {
        if (isset($this->files[$fileId])) {
            unset($this->files[$fileId]);
            return true;
        }
        return false;
    }

    public function search(string $query): array
    {
        $results = [];
        foreach ($this->files as $file) {
            if (isset($file['original_name']) && stripos($file['original_name'], $query) !== false) {
                $results[] = $file;
            }
        }
        return $results;
    }
}