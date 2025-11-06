<?php

namespace App\Application\UseCases;

use App\Domain\Markdown\Services\HtmlSanitizerService;
use App\Domain\Markdown\ValueObjects\HtmlContent;
use App\Domain\Markdown\Entities\SanitizedHtml;

class SanitizeContentUseCase
{
    private HtmlSanitizerService $sanitizer;

    public function __construct(HtmlSanitizerService $sanitizer)
    {
        $this->sanitizer = $sanitizer;
    }

    public function execute(string $htmlContent): SanitizedHtml
    {
        $html = new HtmlContent($htmlContent);
        return $this->sanitizer->sanitizeHtmlContent($html);
    }
}