<?php

// Simple test to verify file creation

$requiredFiles = [
    'src/Component/RequestHandlerInterface.php',
    'src/Component/HttpRequest.php',
    'src/Component/HttpResponse.php',
    'src/Component/BaseRequestHandler.php',
    'src/Decorator/MiddlewareDecorator.php',
    'src/Decorator/AuthMiddleware.php',
    'src/Decorator/LoggingMiddleware.php',
    'src/Decorator/RateLimitMiddleware.php',
    'src/Decorator/CorsMiddleware.php',
    'src/Decorator/CompressionMiddleware.php',
    'src/Service/MiddlewareService.php',
    'src/Service/PipelineBuilder.php',
    'src/Controller/MiddlewareController.php',
    'src/Controller/RequestController.php',
    'routes/api.php',
    'public/index.php'
];

echo "Verifying Decorator Pattern implementation...\n\n";

$allFilesExist = true;

foreach ($requiredFiles as $file) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        echo "✓ $file - EXISTS\n";
    } else {
        echo "✗ $file - MISSING\n";
        $allFilesExist = false;
    }
}

echo "\n" . str_repeat('=', 50) . "\n";

if ($allFilesExist) {
    echo "✅ All required files have been created successfully!\n\n";
    echo "Components include:\n";
    echo "- Component Interface and Base Classes\n";
    echo "- Decorator Base Class and Concrete Implementations\n";
    echo "- Service Classes for Middleware Management\n";
    echo "- Controllers for API Endpoints\n";
    echo "- Route Definitions\n";
} else {
    echo "❌ Some required files are missing. Please check the implementation.\n";
}

echo "\n" . str_repeat('=', 50) . "\n";