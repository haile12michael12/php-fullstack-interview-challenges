<?php

namespace App\Controller;

use App\Core\Traits\ResponseTrait;
use App\Service\BatchProcessor;

class BatchController
{
    use ResponseTrait;

    private BatchProcessor $batchProcessor;

    public function __construct(BatchProcessor $batchProcessor)
    {
        $this->batchProcessor = $batchProcessor;
    }

    public function processBatch(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->errorResponse('Method not allowed', 405);
            return;
        }

        $imagePaths = $_POST['images'] ?? [];
        $operations = $_POST['operations'] ?? [];

        if (empty($imagePaths)) {
            $this->errorResponse('No images provided', 400);
            return;
        }

        if (empty($operations)) {
            $this->errorResponse('No operations provided', 400);
            return;
        }

        try {
            $batchId = $this->batchProcessor->queueBatch($imagePaths, $operations);
            $this->successResponse('Batch processing queued', [
                'batch_id' => $batchId
            ]);
        } catch (\Exception $e) {
            $this->errorResponse('Failed to queue batch processing: ' . $e->getMessage(), 500);
        }
    }

    public function getBatchStatus(string $batchId): void
    {
        try {
            $status = $this->batchProcessor->getBatchStatus($batchId);
            $this->successResponse('Batch status retrieved', $status);
        } catch (\Exception $e) {
            $this->errorResponse('Failed to get batch status: ' . $e->getMessage(), 500);
        }
    }
}