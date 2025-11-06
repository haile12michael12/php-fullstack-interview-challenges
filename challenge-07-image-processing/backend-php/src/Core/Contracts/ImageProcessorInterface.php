<?php

namespace App\Core\Contracts;

interface ImageProcessorInterface
{
    public function process(string $imagePath, array $options = []): string;
    public function resize(string $imagePath, int $width, int $height): string;
    public function crop(string $imagePath, int $x, int $y, int $width, int $height): string;
}