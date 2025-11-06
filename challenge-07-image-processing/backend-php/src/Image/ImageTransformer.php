<?php

namespace App\Image;

class ImageTransformer
{
    public function rotate(string $imagePath, float $angle): string
    {
        // Implementation for rotating an image
        return $imagePath;
    }

    public function flip(string $imagePath, string $direction = 'horizontal'): string
    {
        // Implementation for flipping an image
        return $imagePath;
    }

    public function adjustBrightness(string $imagePath, int $level): string
    {
        // Implementation for adjusting brightness
        return $imagePath;
    }

    public function adjustContrast(string $imagePath, int $level): string
    {
        // Implementation for adjusting contrast
        return $imagePath;
    }
}