<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use App\Application\UseCases\GeneratePreviewUseCase;
use App\Domain\Markdown\Services\RenderMarkdownService;
use App\Domain\Markdown\Services\MarkdownParserService;
use App\Domain\Markdown\Services\HtmlSanitizerService;

class GeneratePreviewUseCaseTest extends TestCase
{
    private GeneratePreviewUseCase $useCase;

    protected function setUp(): void
    {
        $parser = new MarkdownParserService();
        $sanitizer = new HtmlSanitizerService();
        $renderService = new RenderMarkdownService($parser, $sanitizer);
        $this->useCase = new GeneratePreviewUseCase($renderService);
    }

    public function testExecuteReturnsPreviewResponseDTO(): void
    {
        $markdown = "# Test\n\nThis is a test.";
        
        $result = $this->useCase->execute($markdown);
        
        $this->assertInstanceOf(\App\Application\DTO\PreviewResponseDTO::class, $result);
        $this->assertEquals($markdown, $result->originalMarkdown);
        $this->assertNotEmpty($result->renderedHtml);
    }
}