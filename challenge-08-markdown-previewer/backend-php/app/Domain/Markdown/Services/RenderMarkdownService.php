<?php

namespace App\Domain\Markdown\Services;

use App\Domain\Markdown\ValueObjects\MarkdownText;
use App\Domain\Markdown\ValueObjects\HtmlContent;
use App\Domain\Markdown\Entities\SanitizedHtml;

class RenderMarkdownService
{
    private MarkdownParserService $parser;
    private HtmlSanitizerService $sanitizer;

    public function __construct(
        MarkdownParserService $parser,
        HtmlSanitizerService $sanitizer
    ) {
        $this->parser = $parser;
        $this->sanitizer = $sanitizer;
    }

    public function render(MarkdownText $markdownText): SanitizedHtml
    {
        $htmlContent = $this->parser->parseMarkdownText($markdownText);
        return $this->sanitizer->sanitizeHtmlContent($htmlContent);
    }
}