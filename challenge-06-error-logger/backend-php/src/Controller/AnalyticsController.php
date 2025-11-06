<?php

namespace App\Controller;

use App\Service\AnalyticsService;

class AnalyticsController
{
    private AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index(): void
    {
        $analytics = $this->analyticsService->getAnalytics();
        header('Content-Type: application/json');
        echo json_encode(['analytics' => $analytics]);
    }

    public function errorsByLevel(): void
    {
        $data = $this->analyticsService->getErrorsByLevel();
        header('Content-Type: application/json');
        echo json_encode(['errors_by_level' => $data]);
    }

    public function errorsByDay(): void
    {
        $data = $this->analyticsService->getErrorsByDay();
        header('Content-Type: application/json');
        echo json_encode(['errors_by_day' => $data]);
    }
}