<?php

namespace App\Domain\Markdown\ValueObjects;

class MarkdownText
{
    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }
}