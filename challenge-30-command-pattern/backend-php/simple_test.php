<?php

// Simple test to verify file creation

$requiredFiles = [
    'src/Command/CommandInterface.php',
    'src/Command/AbstractCommand.php',
    'src/Command/CreateFileCommand.php',
    'src/Command/DeleteFileCommand.php',
    'src/Command/DatabaseCommand.php',
    'src/Command/SendEmailCommand.php',
    'src/Command/ProcessImageCommand.php',
    'src/Invoker/CommandQueue.php',
    'src/Invoker/CommandHistory.php',
    'src/Invoker/Worker.php',
    'src/Receiver/FileSystemManager.php',
    'src/Receiver/DatabaseManager.php',
    'src/Receiver/EmailService.php',
    'src/Receiver/ImageProcessor.php',
    'src/Service/CommandService.php'
];

echo "Verifying Command Pattern implementation...\n\n";

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
    echo "- Command Interface and Abstract Base Class\n";
    echo "- Concrete Command Implementations\n";
    echo "- Invoker Classes (Queue, History, Worker)\n";
    echo "- Receiver Classes (File System, Database, Email, Image)\n";
    echo "- Service Class for Command Management\n";
} else {
    echo "❌ Some required files are missing. Please check the implementation.\n";
}

echo "\n" . str_repeat('=', 50) . "\n";