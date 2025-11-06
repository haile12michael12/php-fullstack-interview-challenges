<?php

namespace Challenge04\Upload;

use Challenge04\Contracts\UploadServiceInterface;
use Challenge04\Contracts\FileValidatorInterface;
use Challenge04\Contracts\FileScannerInterface;
use Challenge04\Contracts\FileStorageInterface;

class UploadService implements UploadServiceInterface
{
    private FileValidatorInterface $validator;
    private FileScannerInterface $scanner;
    private FileStorageInterface $storage;
    private array $uploads = [];

    public function __construct(
        FileValidatorInterface $validator,
        FileScannerInterface $scanner,
        FileStorageInterface $storage
    ) {
        $this->validator = $validator;
        $this->scanner = $scanner;
        $this->storage = $storage;
    }

    public function uploadFile(array $fileData): array
    {
        $uploadId = uniqid();
        
        try {
            // Step 1: Validate file
            if (!$this->validator->validateFileSize($fileData['tmp_name'], 5242880)) { // 5MB
                throw new \Exception('File validation failed: ' . implode(', ', $this->validator->getValidationErrors()));
            }
            
            // Step 2: Store file temporarily
            $tempPath = $this->storage->storeFile($fileData['tmp_name'], 'temp_' . $uploadId);
            
            // Step 3: Scan file
            $scanResult = $this->scanner->scanFile($tempPath);
            
            if (!$scanResult['is_safe']) {
                // Move to quarantine
                // In a real implementation, you would move the file to a quarantine area
                throw new \Exception('File scan detected threats: ' . implode(', ', $scanResult['threats_found']));
            }
            
            // Step 4: Store file permanently
            $storedPath = $this->storage->storeFile($tempPath, $fileData['name']);
            
            // Step 5: Record upload
            $uploadRecord = [
                'id' => $uploadId,
                'original_name' => $fileData['name'],
                'stored_path' => $storedPath,
                'status' => 'completed',
                'scan_result' => $scanResult,
                'upload_time' => time()
            ];
            
            $this->uploads[$uploadId] = $uploadRecord;
            
            return $uploadRecord;
        } catch (\Exception $e) {
            $this->uploads[$uploadId] = [
                'id' => $uploadId,
                'original_name' => $fileData['name'] ?? 'unknown',
                'status' => 'failed',
                'error' => $e->getMessage(),
                'upload_time' => time()
            ];
            
            throw $e;
        }
    }

    public function getUploadStatus(string $uploadId): array
    {
        return $this->uploads[$uploadId] ?? [];
    }

    public function cancelUpload(string $uploadId): bool
    {
        if (isset($this->uploads[$uploadId])) {
            $this->uploads[$uploadId]['status'] = 'cancelled';
            return true;
        }
        
        return false;
    }
}