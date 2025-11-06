<?php

namespace App\Core\Contracts;

interface SanitizerInterface
{
    public function sanitize(string $html): string;
}