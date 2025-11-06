<?php

namespace App\Domain\Markdown\Services;

use App\Core\Contracts\SanitizerInterface;
use App\Domain\Markdown\ValueObjects\HtmlContent;
use App\Domain\Markdown\Entities\SanitizedHtml;

class HtmlSanitizerService implements SanitizerInterface
{
    public function sanitize(string $html): string
    {
        // Simple HTML sanitization
        // In a real application, you would use a library like HTMLPurifier
        
        // Remove script tags
        $html = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $html);
        
        // Remove on* attributes
        $html = preg_replace('/\s*on\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);
        
        // Allow only safe tags
        $allowedTags = '<h1><h2><h3><h4><h5><h6><p><br><strong><em><u><ol><ul><li><a><img>';
        $html = strip_tags($html, $allowedTags);
        
        return $html;
    }

    public function sanitizeHtmlContent(HtmlContent $htmlContent): SanitizedHtml
    {
        $sanitizedHtml = $this->sanitize($htmlContent->getValue());
        return new SanitizedHtml(new HtmlContent($sanitizedHtml));
    }
}