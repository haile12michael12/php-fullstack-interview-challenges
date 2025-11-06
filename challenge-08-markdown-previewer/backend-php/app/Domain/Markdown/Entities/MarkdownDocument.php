<?php

namespace App\Domain\Markdown\Entities;

use App\Domain\Markdown\ValueObjects\MarkdownText;

class MarkdownDocument
{
    private MarkdownText $content;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(MarkdownText $content)
    {
        $this->content = $content;
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = null;
    }

    public function getContent(): MarkdownText
    {
        return $this->content;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function updateContent(MarkdownText $content): void
    {
        $this->content = $content;
        $this->updatedAt = new \DateTimeImmutable();
    }
}