<?php

namespace App\Image;

use App\Core\Contracts\ImageProcessorInterface;

class ImageProcessor implements ImageProcessorInterface
{
    public function process(string $imagePath, array $options = []): string
    {
        // Implementation for processing an image
        return $imagePath;
    }

    public function resize(string $imagePath, int $width, int $height): string
    {
        // Implementation for resizing an image
        return $imagePath;
    }

    public function crop(string $imagePath, int $x, int $y, int $width, int $height): string
    {
        // Implementation for cropping an image
        return $imagePath;
    }
}