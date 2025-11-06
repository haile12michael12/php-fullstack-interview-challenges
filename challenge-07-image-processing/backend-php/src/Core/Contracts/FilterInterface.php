<?php

namespace App\Core\Contracts;

interface FilterInterface
{
    public function apply(string $imagePath, string $filterType, array $options = []): string;
    public function grayscale(string $imagePath): string;
    public function sepia(string $imagePath): string;
    public function blur(string $imagePath, float $blurLevel = 1.0): string;
}