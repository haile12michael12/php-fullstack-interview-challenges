<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Domain\Markdown\Services\HtmlSanitizerService;
use App\Domain\Markdown\ValueObjects\HtmlContent;
use App\Domain\Markdown\Entities\SanitizedHtml;

class HtmlSanitizerServiceTest extends TestCase
{
    private HtmlSanitizerService $sanitizer;

    protected function setUp(): void
    {
        $this->sanitizer = new HtmlSanitizerService();
    }

    public function testSanitizeRemovesScriptTags(): void
    {
        $html = "<p>Hello</p><script>alert('xss')</script><p>World</p>";
        $expected = "<p>Hello</p><p>World</p>";
        
        $result = $this->sanitizer->sanitize($html);
        
        $this->assertEquals($expected, $result);
    }

    public function testSanitizeHtmlContent(): void
    {
        $htmlContent = new HtmlContent("<h1>Title</h1><script>bad</script>");
        $expectedHtml = "<h1>Title</h1>";
        
        $result = $this->sanitizer->sanitizeHtmlContent($htmlContent);
        
        $this->assertInstanceOf(SanitizedHtml::class, $result);
        $this->assertEquals($expectedHtml, $result->getContent()->getValue());
    }
}