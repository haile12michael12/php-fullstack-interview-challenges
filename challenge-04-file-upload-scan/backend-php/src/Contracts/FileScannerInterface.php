<?php

namespace Challenge04\Contracts;

interface FileScannerInterface
{
    public function scanFile(string $filePath): array;
    public function isFileSafe(string $filePath): bool;
    public function getScanResults(string $fileId): array;
}