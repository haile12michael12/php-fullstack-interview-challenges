<?php

require_once __DIR__ . '/../src/bootstrap.php';

use Challenge04\Core\Response;
use Challenge04\Upload\FileUploader;
use Challenge04\Upload\FileValidator;
use Challenge04\Upload\FileScanner;
use Challenge04\Storage\LocalFileStorage;
use Challenge04\Storage\FileRepository;
use Challenge04\Exception\UploadException;
use Challenge04\Exception\ValidationException;

// Simple router
$path = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

switch ($path) {
    case '/api/upload':
        if ($method === 'POST') {
            handleUpload();
        } else {
            Response::error('Method not allowed', 405)->send();
        }
        break;
        
    case '/api/files':
        if ($method === 'GET') {
            handleListFiles();
        } else {
            Response::error('Method not allowed', 405)->send();
        }
        break;
        
    default:
        if (strpos($path, '/api/files/') === 0 && $method === 'GET') {
            $parts = explode('/', trim($path, '/'));
            if (count($parts) === 3) {
                handleGetFile($parts[2]);
            } else {
                Response::error('Not found', 404)->send();
            }
        } else {
            Response::error('Not found', 404)->send();
        }
        break;
}

function handleUpload(): void
{
    try {
        if (!isset($_FILES['file'])) {
            Response::error('No file uploaded', 400)->send();
            return;
        }
        
        $fileData = $_FILES['file'];
        
        // Create services
        $validator = new FileValidator();
        $scanner = new FileScanner();
        $storage = new LocalFileStorage();
        $repository = new FileRepository();
        
        // Validate file
        if (!$validator->validateFileSize($fileData['tmp_name'], MAX_FILE_SIZE)) {
            Response::error('File too large', 400)->send();
            return;
        }
        
        // Upload file
        $uploader = new FileUploader(UPLOAD_PATH, MAX_FILE_SIZE);
        $uploadResult = $uploader->upload($fileData);
        
        // Scan file
        $scanResult = $scanner->scanFile($uploadResult['file_path']);
        
        if (!$scanResult['is_safe']) {
            // Move to quarantine
            $quarantineStorage = new LocalFileStorage(QUARANTINE_PATH);
            $quarantineStorage->storeFile($uploadResult['file_path'], $uploadResult['stored_name']);
            unlink($uploadResult['file_path']); // Delete original
            
            Response::error('File contains threats and has been quarantined', 400)->send();
            return;
        }
        
        // Save file metadata
        $fileRecord = [
            'id' => uniqid(),
            'original_name' => $uploadResult['original_name'],
            'stored_name' => $uploadResult['stored_name'],
            'file_path' => $uploadResult['file_path'],
            'file_size' => $uploadResult['file_size'],
            'scan_result' => $scanResult,
            'upload_time' => $uploadResult['upload_time']
        ];
        
        $repository->save($fileRecord);
        
        Response::json([
            'status' => 'success',
            'message' => 'File uploaded successfully',
            'file' => $fileRecord
        ])->send();
    } catch (Exception $e) {
        Response::error($e->getMessage(), 500)->send();
    }
}

function handleListFiles(): void
{
    try {
        $repository = new FileRepository();
        $files = $repository->findAll();
        
        Response::json([
            'status' => 'success',
            'files' => array_values($files)
        ])->send();
    } catch (Exception $e) {
        Response::error($e->getMessage(), 500)->send();
    }
}

function handleGetFile(string $fileId): void
{
    try {
        $repository = new FileRepository();
        $file = $repository->findById($fileId);
        
        if (!$file) {
            Response::error('File not found', 404)->send();
            return;
        }
        
        Response::json([
            'status' => 'success',
            'file' => $file
        ])->send();
    } catch (Exception $e) {
        Response::error($e->getMessage(), 500)->send();
    }
}