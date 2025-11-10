<?php

/**
 * Main entry point for the Memory Management Challenge API
 */

// Manually include our classes
require_once __DIR__ . '/../src/Api/Router.php';
require_once __DIR__ . '/../src/Api/MemoryController.php';
require_once __DIR__ . '/../src/Memory/Profiler.php';
require_once __DIR__ . '/../src/Memory/Monitor.php';
require_once __DIR__ . '/../src/Memory/Analyzer.php';
require_once __DIR__ . '/../src/Leak/Detector.php';
require_once __DIR__ . '/../src/Optimizer/DataStructureOptimizer.php';
require_once __DIR__ . '/../src/Monitoring/ReportGenerator.php';
require_once __DIR__ . '/../src/Utils/MemoryFormatter.php';

use App\Api\Router;

// Route the request
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

Router::handleRequest($path, $method);