<?php

namespace App\Domain\Markdown\Services;

use App\Core\Contracts\ParserInterface;
use App\Domain\Markdown\ValueObjects\MarkdownText;
use App\Domain\Markdown\ValueObjects\HtmlContent;

class MarkdownParserService implements ParserInterface
{
    public function parse(string $content): string
    {
        // Simple markdown parsing implementation
        // In a real application, you would use a library like parsedown or league/commonmark
        
        // Headers
        $content = preg_replace('/^# (.*)$/m', '<h1>$1</h1>', $content);
        $content = preg_replace('/^## (.*)$/m', '<h2>$1</h2>', $content);
        $content = preg_replace('/^### (.*)$/m', '<h3>$1</h3>', $content);
        
        // Bold and italic
        $content = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $content);
        $content = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $content);
        
        // Lists
        $content = preg_replace('/^\* (.*)$/m', '<li>$1</li>', $content);
        $content = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $content);
        
        // Paragraphs
        $content = preg_replace('/^(?!<[\/]?h[1-6]>)(?!<ul>)(?!<\/ul>)(?!<li>)(?!<\/li>)(.*)$/m', '<p>$1</p>', $content);
        
        return $content;
    }

    public function parseMarkdownText(MarkdownText $markdownText): HtmlContent
    {
        $html = $this->parse($markdownText->getValue());
        return new HtmlContent($html);
    }
}