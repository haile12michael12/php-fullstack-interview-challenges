<?php

namespace App\Core\Helpers;

class ImageUtils
{
    public static function getImageDimensions(string $imagePath): array
    {
        $size = getimagesize($imagePath);
        return [
            'width' => $size[0],
            'height' => $size[1],
            'mime' => $size['mime']
        ];
    }

    public static function generateUniqueFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $basename = pathinfo($originalName, PATHINFO_FILENAME);
        return $basename . '_' . uniqid() . '.' . $extension;
    }

    public static function createDirectoryIfNotExists(string $directory): bool
    {
        if (!is_dir($directory)) {
            return mkdir($directory, 0755, true);
        }
        return true;
    }
}