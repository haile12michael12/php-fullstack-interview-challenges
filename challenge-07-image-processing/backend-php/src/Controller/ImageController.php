<?php

namespace App\Controller;

use App\Core\Traits\ResponseTrait;
use App\Core\Traits\FileValidationTrait;
use App\Service\ImageService;

class ImageController
{
    use ResponseTrait, FileValidationTrait;

    private ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function upload(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->errorResponse('Method not allowed', 405);
            return;
        }

        if (!isset($_FILES['image'])) {
            $this->errorResponse('No image uploaded', 400);
            return;
        }

        $uploadedFile = $_FILES['image'];
        
        if (!$this->isValidImage($uploadedFile['tmp_name'])) {
            $this->errorResponse('Invalid image file', 400);
            return;
        }

        if (!$this->isValidFileSize($uploadedFile['tmp_name'])) {
            $this->errorResponse('Image file too large', 400);
            return;
        }

        // Move uploaded file to uploads directory
        $uploadDir = __DIR__ . '/../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = uniqid() . '_' . basename($uploadedFile['name']);
        $destination = $uploadDir . $filename;
        
        if (move_uploaded_file($uploadedFile['tmp_name'], $destination)) {
            $this->successResponse('Image uploaded successfully', [
                'filename' => $filename,
                'path' => '/uploads/' . $filename
            ]);
        } else {
            $this->errorResponse('Failed to upload image', 500);
        }
    }

    public function process(string $filename): void
    {
        $imagePath = __DIR__ . '/../public/uploads/' . $filename;
        
        if (!file_exists($imagePath)) {
            $this->errorResponse('Image not found', 404);
            return;
        }

        // Get operations from request
        $operations = $_POST['operations'] ?? [];
        
        try {
            $processedImagePath = $this->imageService->processImage($imagePath, $operations);
            $this->successResponse('Image processed successfully', [
                'original' => '/uploads/' . $filename,
                'processed' => '/optimized/' . basename($processedImagePath)
            ]);
        } catch (\Exception $e) {
            $this->errorResponse('Failed to process image: ' . $e->getMessage(), 500);
        }
    }
}