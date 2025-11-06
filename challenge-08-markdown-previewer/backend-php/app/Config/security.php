<?php

return [
    'content_security_policy' => [
        'default_src' => "'self'",
        'script_src' => "'self' 'unsafe-inline'",
        'style_src' => "'self' 'unsafe-inline'",
        'img_src' => "'self' data:",
        'font_src' => "'self'",
        'connect_src' => "'self'",
        'frame_ancestors' => "'none'",
    ],
    'allowed_tags' => '<h1><h2><h3><h4><h5><h6><p><br><strong><em><u><ol><ul><li><a><img>',
    'forbidden_attributes' => [
        'onabort', 'onblur', 'onchange', 'onclick', 'ondblclick', 'onerror', 'onfocus',
        'onkeydown', 'onkeypress', 'onkeyup', 'onload', 'onmousedown', 'onmousemove',
        'onmouseout', 'onmouseover', 'onmouseup', 'onreset', 'onresize', 'onselect',
        'onsubmit', 'onunload'
    ],
];