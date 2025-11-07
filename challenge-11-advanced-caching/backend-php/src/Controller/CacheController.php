<?php

namespace App\Controller;

use App\Service\CacheWarmService;
use App\Service\MonitoringService;
use App\Helpers\ResponseHelper;

class CacheController
{
    private CacheWarmService $cacheWarmService;
    private MonitoringService $monitoringService;
    private ResponseHelper $responseHelper;
    
    public function __construct(
        CacheWarmService $cacheWarmService,
        MonitoringService $monitoringService,
        ResponseHelper $responseHelper
    ) {
        $this->cacheWarmService = $cacheWarmService;
        $this->monitoringService = $monitoringService;
        $this->responseHelper = $responseHelper;
    }

    public function getStats(): string
    {
        try {
            $stats = $this->monitoringService->getPerformanceMetrics();
            return $this->responseHelper->json($stats);
        } catch (\Exception $e) {
            return $this->responseHelper->json([
                'error' => 'Failed to retrieve cache stats',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function warmCache(): string
    {
        try {
            $this->cacheWarmService->warmPopularItems();
            return $this->responseHelper->json([
                'message' => 'Cache warming initiated successfully'
            ]);
        } catch (\Exception $e) {
            return $this->responseHelper->json([
                'error' => 'Failed to warm cache',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function flushCache(): string
    {
        // In a real implementation, this would clear the cache
        // For security reasons, this should be protected
        return $this->responseHelper->json([
            'message' => 'Cache flush functionality would be implemented here'
        ]);
    }

    public function invalidateCache(): string
    {
        // In a real implementation, this would invalidate specific cache entries
        // based on the request data
        $requestData = json_decode(file_get_contents('php://input'), true);
        $keys = $requestData['keys'] ?? [];
        
        return $this->responseHelper->json([
            'message' => 'Cache invalidation functionality would be implemented here',
            'keys' => $keys
        ]);
    }
}