<?php

namespace App\Http\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Service\BusinessLogicService;

class TraitController
{
    private BusinessLogicService $businessLogicService;
    
    public function __construct()
    {
        $this->businessLogicService = new BusinessLogicService();
    }
    
    public function index(Request $request): Response
    {
        return new Response([
            'message' => 'Traits and Anonymous Classes Demo',
            'available_endpoints' => [
                'POST /api/traits/logger' => 'Log a message',
                'POST /api/traits/calculate' => 'Perform cached calculation',
                'POST /api/traits/validate' => 'Validate entity data',
                'POST /api/traits/cache' => 'Cache data',
                'GET /api/traits/cache/{key}' => 'Get cached data',
                'POST /api/traits/users' => 'Create a user',
                'GET /api/traits/stats' => 'Get system stats'
            ]
        ]);
    }
    
    public function logMessage(Request $request): Response
    {
        $data = $request->getParsedBody();
        
        try {
            $result = $this->businessLogicService->executeStrategy('logger', $data);
            return new Response($result);
        } catch (\Exception $e) {
            return new Response([
                'error' => $e->getMessage()
            ], 400);
        }
    }
    
    public function calculate(Request $request): Response
    {
        $data = $request->getParsedBody();
        $number = $data['number'] ?? 5;
        
        $start = microtime(true);
        $result = $this->businessLogicService->performCalculation((int)$number);
        $duration = microtime(true) - $start;
        
        return new Response([
            'number' => $number,
            'result' => $result,
            'calculation_time' => $duration
        ]);
    }
    
    public function validate(Request $request): Response
    {
        $data = $request->getParsedBody();
        
        $result = $this->businessLogicService->validateEntity($data);
        
        return new Response($result);
    }
    
    public function cacheData(Request $request): Response
    {
        $data = $request->getParsedBody();
        $key = $data['key'] ?? 'default';
        $value = $data['value'] ?? null;
        $ttl = $data['ttl'] ?? 3600;
        
        $success = $this->businessLogicService->cacheData($key, $value, $ttl);
        
        return new Response([
            'success' => $success,
            'key' => $key,
            'value' => $value
        ]);
    }
    
    public function getCachedData(Request $request, array $params): Response
    {
        $key = $params['key'] ?? 'default';
        $value = $this->businessLogicService->getCachedData($key);
        
        return new Response([
            'key' => $key,
            'value' => $value
        ]);
    }
    
    public function createUser(Request $request): Response
    {
        $data = $request->getParsedBody();
        
        try {
            $user = $this->businessLogicService->createUser($data);
            return new Response($user, 201);
        } catch (\Exception $e) {
            return new Response([
                'error' => $e->getMessage()
            ], 400);
        }
    }
    
    public function getStats(Request $request): Response
    {
        $stats = $this->businessLogicService->getUserStats();
        return new Response($stats);
    }
}