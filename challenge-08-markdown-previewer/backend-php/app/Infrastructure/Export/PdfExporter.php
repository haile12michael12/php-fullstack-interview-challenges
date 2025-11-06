<?php

namespace App\Infrastructure\Export;

use App\Domain\Markdown\Entities\SanitizedHtml;

class PdfExporter
{
    public function export(SanitizedHtml $sanitizedHtml): string
    {
        // In a real implementation, you would use a library like TCPDF or DomPDF
        // For now, we'll return a placeholder
        
        $html = $sanitizedHtml->__toString();
        
        // This is a simplified representation
        // In practice, you would generate an actual PDF file
        return "PDF Export of:\n" . $html;
    }
}