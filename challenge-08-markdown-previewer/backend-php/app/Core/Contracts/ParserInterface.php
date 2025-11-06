<?php

namespace App\Core\Contracts;

interface ParserInterface
{
    public function parse(string $content): string;
}