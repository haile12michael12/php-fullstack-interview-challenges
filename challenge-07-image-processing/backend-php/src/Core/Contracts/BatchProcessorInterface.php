<?php

namespace App\Core\Contracts;

interface BatchProcessorInterface
{
    public function processBatch(array $imagePaths, array $operations): array;
    public function queueBatch(array $imagePaths, array $operations): string;
    public function getBatchStatus(string $batchId): array;
}