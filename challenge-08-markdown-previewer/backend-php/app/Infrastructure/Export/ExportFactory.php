<?php

namespace App\Infrastructure\Export;

class ExportFactory
{
    public function createExporter(string $format): object
    {
        switch (strtolower($format)) {
            case 'html':
                return new HtmlExporter();
            case 'pdf':
                return new PdfExporter();
            default:
                throw new \InvalidArgumentException("Unsupported export format: {$format}");
        }
    }
}