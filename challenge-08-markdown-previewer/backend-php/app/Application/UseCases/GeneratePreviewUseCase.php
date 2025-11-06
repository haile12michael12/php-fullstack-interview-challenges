<?php

namespace App\Application\UseCases;

use App\Domain\Markdown\ValueObjects\MarkdownText;
use App\Domain\Markdown\Services\RenderMarkdownService;
use App\Domain\Markdown\Entities\SanitizedHtml;
use App\Application\DTO\PreviewResponseDTO;

class GeneratePreviewUseCase
{
    private RenderMarkdownService $renderService;

    public function __construct(RenderMarkdownService $renderService)
    {
        $this->renderService = $renderService;
    }

    public function execute(string $markdownContent): PreviewResponseDTO
    {
        $markdownText = new MarkdownText($markdownContent);
        $sanitizedHtml = $this->renderService->render($markdownText);
        
        return new PreviewResponseDTO(
            $markdownContent,
            $sanitizedHtml->__toString()
        );
    }
}