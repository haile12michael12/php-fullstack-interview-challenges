<?php

// Simple development server for the application
$host = 'localhost';
$port = 8000;

echo "Starting development server at http://{$host}:{$port}\n";
echo "Press Ctrl+C to stop\n";

// Change to the public directory
chdir(__DIR__ . '/public');

// Start the built-in PHP server
passthru("php -S {$host}:{$port}");