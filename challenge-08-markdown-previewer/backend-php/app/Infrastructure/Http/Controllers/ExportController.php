<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UseCases\ExportDocumentUseCase;
use App\Core\Helpers\ResponseHelper;

class ExportController
{
    private ExportDocumentUseCase $exportDocumentUseCase;

    public function __construct(ExportDocumentUseCase $exportDocumentUseCase)
    {
        $this->exportDocumentUseCase = $exportDocumentUseCase;
    }

    public function export(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $markdownContent = $input['markdown'] ?? '';
        $format = $input['format'] ?? 'html';

        try {
            $exportedContent = $this->exportDocumentUseCase->execute($markdownContent, $format);
            
            ResponseHelper::json([
                'status' => 'success',
                'data' => [
                    'content' => $exportedContent,
                    'format' => $format
                ]
            ]);
        } catch (\Exception $e) {
            ResponseHelper::json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}