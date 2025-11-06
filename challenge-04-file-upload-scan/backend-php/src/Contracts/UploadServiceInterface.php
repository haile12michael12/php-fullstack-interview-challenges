<?php

namespace Challenge04\Contracts;

interface UploadServiceInterface
{
    public function uploadFile(array $fileData): array;
    public function getUploadStatus(string $uploadId): array;
    public function cancelUpload(string $uploadId): bool;
}