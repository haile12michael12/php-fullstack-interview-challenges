<?php

namespace App\Application\UseCases;

use App\Domain\Markdown\ValueObjects\MarkdownText;
use App\Domain\Markdown\Services\RenderMarkdownService;
use App\Infrastructure\Export\ExportFactory;

class ExportDocumentUseCase
{
    private RenderMarkdownService $renderService;
    private ExportFactory $exportFactory;

    public function __construct(
        RenderMarkdownService $renderService,
        ExportFactory $exportFactory
    ) {
        $this->renderService = $renderService;
        $this->exportFactory = $exportFactory;
    }

    public function execute(string $markdownContent, string $format): string
    {
        $markdownText = new MarkdownText($markdownContent);
        $sanitizedHtml = $this->renderService->render($markdownText);
        
        $exporter = $this->exportFactory->createExporter($format);
        return $exporter->export($sanitizedHtml);
    }
}