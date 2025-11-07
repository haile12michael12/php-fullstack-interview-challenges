<?php

namespace App\Service;

use App\Cache\CacheManager;
use App\Exception\CacheException;

class CacheWarmService
{
    private CacheManager $cacheManager;
    private UserService $userService;
    private ProductService $productService;
    
    public function __construct(
        CacheManager $cacheManager,
        UserService $userService,
        ProductService $productService
    ) {
        $this->cacheManager = $cacheManager;
        $this->userService = $userService;
        $this->productService = $productService;
    }

    public function warmUserCache(array $userIds): void
    {
        foreach ($userIds as $id) {
            try {
                $this->userService->getUserById($id);
            } catch (\Exception $e) {
                error_log("Failed to warm user cache for ID $id: " . $e->getMessage());
            }
        }
    }

    public function warmProductCache(array $productIds): void
    {
        foreach ($productIds as $id) {
            try {
                $this->productService->getProductById($id);
            } catch (\Exception $e) {
                error_log("Failed to warm product cache for ID $id: " . $e->getMessage());
            }
        }
    }

    public function warmCategoryCache(array $categories, int $limit = 10): void
    {
        foreach ($categories as $category) {
            try {
                $this->productService->getProductsByCategory($category, $limit);
            } catch (\Exception $e) {
                error_log("Failed to warm category cache for $category: " . $e->getMessage());
            }
        }
    }

    public function warmPopularItems(): void
    {
        // Warm cache for popular users (IDs 1-10)
        $this->warmUserCache(range(1, 10));
        
        // Warm cache for popular products (IDs 1-50)
        $this->warmProductCache(range(1, 50));
        
        // Warm cache for popular categories
        $this->warmCategoryCache(['electronics', 'books', 'clothing'], 20);
    }

    public function precomputeExpensiveQueries(): void
    {
        // Precompute and cache expensive queries
        // This could include complex aggregations, reports, etc.
        
        // Example: Cache user statistics
        $this->cacheUserStatistics();
        
        // Example: Cache product recommendations
        $this->cacheProductRecommendations();
    }

    private function cacheUserStatistics(): void
    {
        $cacheKey = 'user_statistics';
        
        try {
            // Simulate expensive database query
            $stats = $this->computeUserStatistics();
            
            // Cache the result for 1 hour
            $this->cacheManager->set($cacheKey, $stats, 3600);
        } catch (CacheException $e) {
            error_log("Failed to cache user statistics: " . $e->getMessage());
        }
    }

    private function cacheProductRecommendations(): void
    {
        $cacheKey = 'product_recommendations';
        
        try {
            // Simulate expensive recommendation algorithm
            $recommendations = $this->computeProductRecommendations();
            
            // Cache the result for 30 minutes
            $this->cacheManager->set($cacheKey, $recommendations, 1800);
        } catch (CacheException $e) {
            error_log("Failed to cache product recommendations: " . $e->getMessage());
        }
    }

    private function computeUserStatistics(): array
    {
        // Simulate expensive database query
        return [
            'total_users' => 1000,
            'active_users' => 750,
            'new_users_today' => 25,
            'timestamp' => time()
        ];
    }

    private function computeProductRecommendations(): array
    {
        // Simulate expensive recommendation algorithm
        return [
            'recommended_products' => [1, 5, 12, 23, 45],
            'trending_products' => [2, 8, 15, 33, 41],
            'timestamp' => time()
        ];
    }
}