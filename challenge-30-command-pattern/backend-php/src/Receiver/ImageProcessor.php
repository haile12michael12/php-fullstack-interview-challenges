<?php

namespace App\Receiver;

class ImageProcessor
{
    private string $storagePath;

    public function __construct(string $storagePath = './storage/images')
    {
        $this->storagePath = rtrim($storagePath, '/');
        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0755, true);
        }
    }

    /**
     * Process an image with specified operations
     *
     * @param string $sourcePath
     * @param string $destinationPath
     * @param array $operations
     * @return bool
     */
    public function process(string $sourcePath, string $destinationPath, array $operations = []): bool
    {
        // In a real implementation, this would use GD or Imagick libraries
        // For this example, we'll simulate image processing by copying and logging operations
        
        $sourceFile = $this->getFilePath($sourcePath);
        $destinationFile = $this->getFilePath($destinationPath);
        
        // Check if source file exists
        if (!file_exists($sourceFile)) {
            throw new \InvalidArgumentException("Source file does not exist: $sourcePath");
        }
        
        // Create destination directory if it doesn't exist
        $destinationDir = dirname($destinationFile);
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }
        
        // Copy the source file to destination
        if (!copy($sourceFile, $destinationFile)) {
            return false;
        }
        
        // Log the operations (in a real implementation, these would be applied to the image)
        $logData = [
            'source' => $sourcePath,
            'destination' => $destinationPath,
            'operations' => $operations,
            'processed_at' => date('c')
        ];
        
        $logFile = $this->storagePath . '/processing.log';
        $logEntry = "[" . date('c') . "] IMAGE PROCESSED: " . json_encode($logData) . "\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
        
        return true;
    }

    /**
     * Resize an image
     *
     * @param string $sourcePath
     * @param string $destinationPath
     * @param int $width
     * @param int $height
     * @return bool
     */
    public function resize(string $sourcePath, string $destinationPath, int $width, int $height): bool
    {
        return $this->process($sourcePath, $destinationPath, [
            'operation' => 'resize',
            'width' => $width,
            'height' => $height
        ]);
    }

    /**
     * Apply a filter to an image
     *
     * @param string $sourcePath
     * @param string $destinationPath
     * @param string $filter
     * @return bool
     */
    public function applyFilter(string $sourcePath, string $destinationPath, string $filter): bool
    {
        return $this->process($sourcePath, $destinationPath, [
            'operation' => 'filter',
            'filter' => $filter
        ]);
    }

    /**
     * Get image information
     *
     * @param string $imagePath
     * @return array
     */
    public function getImageInfo(string $imagePath): array
    {
        $filepath = $this->getFilePath($imagePath);
        
        if (!file_exists($filepath)) {
            return [];
        }
        
        // In a real implementation, this would use getimagesize()
        // For this example, we'll return simulated data
        return [
            'size' => filesize($filepath),
            'modified' => filemtime($filepath),
            'is_readable' => is_readable($filepath),
            'is_writable' => is_writable($filepath)
        ];
    }

    /**
     * Get processing log
     *
     * @param int $limit
     * @return array
     */
    public function getProcessingLog(int $limit = 10): array
    {
        $logFile = $this->storagePath . '/processing.log';
        
        if (!file_exists($logFile)) {
            return [];
        }
        
        $lines = file($logFile, FILE_IGNORE_NEW_LINES);
        $entries = [];
        
        // Get the last $limit lines
        $lines = array_slice($lines, -$limit);
        
        foreach ($lines as $line) {
            if (preg_match('/\[([^\]]+)\] IMAGE PROCESSED: (.+)/', $line, $matches)) {
                $entries[] = [
                    'timestamp' => $matches[1],
                    'data' => json_decode($matches[2], true)
                ];
            }
        }
        
        return array_reverse($entries);
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
        return $this->storagePath . '/' . ltrim($filename, '/');
    }
}