<?php

declare(strict_types=1);

// Front Controller for the Markdown Previewer API

require_once __DIR__ . '/../app/bootstrap.php';

// Route all requests to the API router
require_once __DIR__ . '/../app/Infrastructure/Http/Routes/api.php';