<?php

namespace SharedBackend\Storage;

use SharedBackend\Core\Config;
use SharedBackend\Core\Exceptions\StorageException;

class FileStorage implements StorageInterface
{
    private $basePath;
    private $config;
    
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->basePath = $config->get('storage.path', __DIR__ . '/../../../../storage');
        
        if (!is_dir($this->basePath) && !mkdir($this->basePath, 0755, true)) {
            throw new StorageException("Unable to create storage directory: {$this->basePath}");
        }
    }
    
    /**
     * Store a file
     * 
     * @param string $path Relative path within storage
     * @param string $contents File contents
     * @return bool Success
     */
    public function put(string $path, string $contents): bool
    {
        $fullPath = $this->getFullPath($path);
        $directory = dirname($fullPath);
        
        if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
            throw new StorageException("Unable to create directory: {$directory}");
        }
        
        return file_put_contents($fullPath, $contents) !== false;
    }
    
    /**
     * Get file contents
     * 
     * @param string $path Relative path within storage
     * @return string|null File contents or null if not found
     */
    public function get(string $path): ?string
    {
        $fullPath = $this->getFullPath($path);
        
        if (!file_exists($fullPath)) {
            return null;
        }
        
        return file_get_contents($fullPath);
    }
    
    /**
     * Check if a file exists
     * 
     * @param string $path Relative path within storage
     * @return bool
     */
    public function exists(string $path): bool
    {
        return file_exists($this->getFullPath($path));
    }
    
    /**
     * Delete a file
     * 
     * @param string $path Relative path within storage
     * @return bool Success
     */
    public function delete(string $path): bool
    {
        $fullPath = $this->getFullPath($path);
        
        if (!file_exists($fullPath)) {
            return true;
        }
        
        return unlink($fullPath);
    }
    
    /**
     * Get the full path for a relative path
     * 
     * @param string $path
     * @return string
     */
    private function getFullPath(string $path): string
    {
        return $this->basePath . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
    }
}