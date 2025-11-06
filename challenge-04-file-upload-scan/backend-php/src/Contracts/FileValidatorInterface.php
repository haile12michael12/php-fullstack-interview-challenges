<?php

namespace Challenge04\Contracts;

interface FileValidatorInterface
{
    public function validateFileSize(string $filePath, int $maxSize): bool;
    public function validateFileType(string $filePath, array $allowedTypes): bool;
    public function validateFileContent(string $filePath): bool;
    public function getValidationErrors(): array;
}