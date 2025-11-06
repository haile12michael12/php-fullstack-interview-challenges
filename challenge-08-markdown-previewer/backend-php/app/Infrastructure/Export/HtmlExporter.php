<?php

namespace App\Infrastructure\Export;

use App\Domain\Markdown\Entities\SanitizedHtml;

class HtmlExporter
{
    public function export(SanitizedHtml $sanitizedHtml): string
    {
        $html = $sanitizedHtml->__toString();
        
        // Wrap in basic HTML structure
        $fullHtml = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Exported Document</title>
</head>
<body>
    {$html}
</body>
</html>
HTML;

        return $fullHtml;
    }
}