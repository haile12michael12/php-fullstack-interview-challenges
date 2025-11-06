<?php

namespace App\Core\Contracts;

interface OptimizerInterface
{
    public function optimize(string $imagePath, array $options = []): string;
    public function compress(string $imagePath, int $quality = 80): string;
    public function resizeAndOptimize(string $imagePath, int $width, int $height, int $quality = 80): string;
}