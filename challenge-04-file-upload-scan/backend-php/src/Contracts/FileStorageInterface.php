<?php

namespace Challenge04\Contracts;

interface FileStorageInterface
{
    public function storeFile(string $sourcePath, string $destinationName): string;
    public function retrieveFile(string $fileId): string;
    public function deleteFile(string $fileId): bool;
    public function getFileMetadata(string $fileId): array;
}