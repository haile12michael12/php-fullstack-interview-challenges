<?php

namespace App\Application\DTO;

class PreviewResponseDTO
{
    public string $originalMarkdown;
    public string $renderedHtml;

    public function __construct(string $originalMarkdown, string $renderedHtml)
    {
        $this->originalMarkdown = $originalMarkdown;
        $this->renderedHtml = $renderedHtml;
    }

    public function toArray(): array
    {
        return [
            'originalMarkdown' => $this->originalMarkdown,
            'renderedHtml' => $this->renderedHtml
        ];
    }
}