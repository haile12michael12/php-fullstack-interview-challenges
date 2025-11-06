<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\GeneratePreviewUseCase;
use App\Core\Helpers\ResponseHelper;

class PreviewController
{
    private GeneratePreviewUseCase $generatePreviewUseCase;

    public function __construct(GeneratePreviewUseCase $generatePreviewUseCase)
    {
        $this->generatePreviewUseCase = $generatePreviewUseCase;
    }

    public function preview(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $markdownContent = $input['markdown'] ?? '';

        try {
            $previewDTO = $this->generatePreviewUseCase->execute($markdownContent);
            
            ResponseHelper::json([
                'status' => 'success',
                'data' => $previewDTO->toArray()
            ]);
        } catch (\Exception $e) {
            ResponseHelper::json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}