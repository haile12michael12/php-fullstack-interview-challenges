<?php

namespace App\Infrastructure\Security;

class SanitizationRules
{
    public static function getAllowedTags(): string
    {
        return '<h1><h2><h3><h4><h5><h6><p><br><strong><em><u><ol><ul><li><a><img>';
    }

    public static function getAllowedAttributes(): array
    {
        return [
            'a' => ['href', 'title'],
            'img' => ['src', 'alt', 'title']
        ];
    }

    public static function getForbiddenAttributes(): array
    {
        return [
            'onabort', 'onblur', 'onchange', 'onclick', 'ondblclick', 'onerror', 'onfocus', 
            'onkeydown', 'onkeypress', 'onkeyup', 'onload', 'onmousedown', 'onmousemove', 
            'onmouseout', 'onmouseover', 'onmouseup', 'onreset', 'onresize', 'onselect', 
            'onsubmit', 'onunload'
        ];
    }
}