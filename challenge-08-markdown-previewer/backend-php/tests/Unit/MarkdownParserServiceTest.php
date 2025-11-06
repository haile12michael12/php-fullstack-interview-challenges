<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Domain\Markdown\Services\MarkdownParserService;
use App\Domain\Markdown\ValueObjects\MarkdownText;
use App\Domain\Markdown\ValueObjects\HtmlContent;

class MarkdownParserServiceTest extends TestCase
{
    private MarkdownParserService $parser;

    protected function setUp(): void
    {
        $this->parser = new MarkdownParserService();
    }

    public function testParseSimpleMarkdown(): void
    {
        $markdown = "# Hello World\n\nThis is a **bold** text.";
        $expected = "<h1>Hello World</h1>\n\n<p>This is a <strong>bold</strong> text.</p>";
        
        $result = $this->parser->parse($markdown);
        
        $this->assertEquals($expected, $result);
    }

    public function testParseMarkdownText(): void
    {
        $markdownText = new MarkdownText("# Test Header");
        $expected = new HtmlContent("<h1>Test Header</h1>");
        
        $result = $this->parser->parseMarkdownText($markdownText);
        
        $this->assertInstanceOf(HtmlContent::class, $result);
        $this->assertEquals("<h1>Test Header</h1>", $result->getValue());
    }
}