<?php

namespace SharedBackend\Storage;

interface StorageInterface
{
    /**
     * Store a file
     * 
     * @param string $path Relative path within storage
     * @param string $contents File contents
     * @return bool Success
     */
    public function put(string $path, string $contents): bool;
    
    /**
     * Get file contents
     * 
     * @param string $path Relative path within storage
     * @return string|null File contents or null if not found
     */
    public function get(string $path): ?string;
    
    /**
     * Check if a file exists
     * 
     * @param string $path Relative path within storage
     * @return bool
     */
    public function exists(string $path): bool;
    
    /**
     * Delete a file
     * 
     * @param string $path Relative path within storage
     * @return bool Success
     */
    public function delete(string $path): bool;
}