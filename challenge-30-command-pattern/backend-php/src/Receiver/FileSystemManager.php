<?php

namespace App\Receiver;

class FileSystemManager
{
    private string $basePath;

    public function __construct(string $basePath = './storage')
    {
        $this->basePath = rtrim($basePath, '/');
        if (!is_dir($this->basePath)) {
            mkdir($this->basePath, 0755, true);
        }
    }

    /**
     * Create a file with content
     *
     * @param string $filename
     * @param string $content
     * @return bool
     */
    public function createFile(string $filename, string $content = ''): bool
    {
        $filepath = $this->getFilePath($filename);
        $dir = dirname($filepath);
        
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        return file_put_contents($filepath, $content) !== false;
    }

    /**
     * Read a file's content
     *
     * @param string $filename
     * @return string
     */
    public function readFile(string $filename): string
    {
        $filepath = $this->getFilePath($filename);
        
        if (!file_exists($filepath)) {
            return '';
        }
        
        return file_get_contents($filepath);
    }

    /**
     * Update a file's content
     *
     * @param string $filename
     * @param string $content
     * @return bool
     */
    public function updateFile(string $filename, string $content): bool
    {
        $filepath = $this->getFilePath($filename);
        
        if (!file_exists($filepath)) {
            return false;
        }
        
        return file_put_contents($filepath, $content) !== false;
    }

    /**
     * Delete a file
     *
     * @param string $filename
     * @return bool
     */
    public function deleteFile(string $filename): bool
    {
        $filepath = $this->getFilePath($filename);
        
        if (!file_exists($filepath)) {
            return false;
        }
        
        return unlink($filepath);
    }

    /**
     * Check if a file exists
     *
     * @param string $filename
     * @return bool
     */
    public function fileExists(string $filename): bool
    {
        $filepath = $this->getFilePath($filename);
        return file_exists($filepath);
    }

    /**
     * Get file information
     *
     * @param string $filename
     * @return array
     */
    public function getFileInfo(string $filename): array
    {
        $filepath = $this->getFilePath($filename);
        
        if (!file_exists($filepath)) {
            return [];
        }
        
        return [
            'size' => filesize($filepath),
            'modified' => filemtime($filepath),
            'permissions' => fileperms($filepath),
            'is_readable' => is_readable($filepath),
            'is_writable' => is_writable($filepath)
        ];
    }

    /**
     * Get the full file path
     *
     * @param string $filename
     * @return string
     */
    private function getFilePath(string $filename): string
    {
        // Prevent directory traversal
        $filename = str_replace(['../', '..\\'], '', $filename);
        return $this->basePath . '/' . ltrim($filename, '/');
    }
}