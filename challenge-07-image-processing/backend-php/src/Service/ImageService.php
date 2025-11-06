<?php

namespace App\Service;

use App\Image\ImageProcessor;
use App\Image\ImageFilter;
use App\Image\ImageOptimizer;

class ImageService
{
    private ImageProcessor $processor;
    private ImageFilter $filter;
    private ImageOptimizer $optimizer;

    public function __construct(
        ImageProcessor $processor,
        ImageFilter $filter,
        ImageOptimizer $optimizer
    ) {
        $this->processor = $processor;
        $this->filter = $filter;
        $this->optimizer = $optimizer;
    }

    public function processImage(string $imagePath, array $operations): string
    {
        $processedImage = $imagePath;
        
        foreach ($operations as $operation) {
            switch ($operation['type']) {
                case 'resize':
                    $processedImage = $this->processor->resize(
                        $processedImage,
                        $operation['width'],
                        $operation['height']
                    );
                    break;
                case 'filter':
                    $processedImage = $this->filter->apply(
                        $processedImage,
                        $operation['filter'],
                        $operation['options'] ?? []
                    );
                    break;
                case 'optimize':
                    $processedImage = $this->optimizer->optimize(
                        $processedImage,
                        $operation['options'] ?? []
                    );
                    break;
            }
        }
        
        return $processedImage;
    }
}