<?php

namespace App\Domain\Markdown\Entities;

use App\Domain\Markdown\ValueObjects\HtmlContent;

class SanitizedHtml
{
    private HtmlContent $content;

    public function __construct(HtmlContent $content)
    {
        $this->content = $content;
    }

    public function getContent(): HtmlContent
    {
        return $this->content;
    }

    public function __toString(): string
    {
        return $this->content->getValue();
    }
}