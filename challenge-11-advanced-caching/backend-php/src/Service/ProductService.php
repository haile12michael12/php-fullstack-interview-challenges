<?php

namespace App\Service;

use App\Cache\CacheManager;
use App\Exception\CacheException;

class ProductService
{
    private CacheManager $cacheManager;
    
    public function __construct(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    public function getProductById(int $id): ?array
    {
        $cacheKey = "product_$id";
        
        try {
            // Try to get from cache first
            $product = $this->cacheManager->get($cacheKey);
            
            if ($product !== null) {
                return $product;
            }
        } catch (CacheException $e) {
            // Log the error but continue to fetch from database
            error_log("Cache error: " . $e->getMessage());
        }

        // Simulate database fetch
        $product = $this->fetchProductFromDatabase($id);
        
        if ($product !== null) {
            // Cache the result
            try {
                $this->cacheManager->set($cacheKey, $product, 1800); // Cache for 30 minutes
            } catch (CacheException $e) {
                // Log the error but don't fail the request
                error_log("Failed to cache product: " . $e->getMessage());
            }
        }
        
        return $product;
    }

    public function getProductsByCategory(string $category, int $limit = 10): array
    {
        $cacheKey = "products_category_{$category}_limit_{$limit}";
        
        try {
            // Try to get from cache first
            $products = $this->cacheManager->get($cacheKey);
            
            if ($products !== null) {
                return $products;
            }
        } catch (CacheException $e) {
            // Log the error but continue to fetch from database
            error_log("Cache error: " . $e->getMessage());
        }

        // Simulate database fetch
        $products = $this->fetchProductsByCategoryFromDatabase($category, $limit);
        
        // Cache the result
        try {
            $this->cacheManager->set($cacheKey, $products, 900); // Cache for 15 minutes
        } catch (CacheException $e) {
            // Log the error but don't fail the request
            error_log("Failed to cache products: " . $e->getMessage());
        }
        
        return $products;
    }

    public function updateProduct(int $id, array $data): bool
    {
        // Simulate database update
        $success = $this->updateProductInDatabase($id, $data);
        
        if ($success) {
            // Invalidate cache
            $cacheKey = "product_$id";
            try {
                $this->cacheManager->delete($cacheKey);
            } catch (CacheException $e) {
                // Log the error but don't fail the request
                error_log("Failed to invalidate product cache: " . $e->getMessage());
            }
        }
        
        return $success;
    }

    private function fetchProductFromDatabase(int $id): ?array
    {
        // Simulate database fetch
        // In a real application, this would query a database
        if ($id <= 0) {
            return null;
        }
        
        return [
            'id' => $id,
            'name' => "Product $id",
            'description' => "Description for product $id",
            'price' => rand(10, 1000) / 10,
            'category' => ['electronics', 'books', 'clothing'][rand(0, 2)],
            'created_at' => date('Y-m-d H:i:s')
        ];
    }

    private function fetchProductsByCategoryFromDatabase(string $category, int $limit): array
    {
        // Simulate database fetch
        // In a real application, this would query a database
        $products = [];
        
        for ($i = 1; $i <= min($limit, 20); $i++) {
            $products[] = [
                'id' => $i,
                'name' => "Product $i",
                'description' => "Description for product $i",
                'price' => rand(10, 1000) / 10,
                'category' => $category,
                'created_at' => date('Y-m-d H:i:s', time() - rand(0, 3600 * 24 * 30))
            ];
        }
        
        return $products;
    }

    private function updateProductInDatabase(int $id, array $data): bool
    {
        // Simulate database update
        // In a real application, this would update a database record
        return $id > 0;
    }
}