<?php

namespace App\Controller;

use App\Service\ProductService;
use App\Helpers\ResponseHelper;

class ProductController
{
    private ProductService $productService;
    private ResponseHelper $responseHelper;
    
    public function __construct(
        ProductService $productService,
        ResponseHelper $responseHelper
    ) {
        $this->productService = $productService;
        $this->responseHelper = $responseHelper;
    }

    public function getProduct(array $params): string
    {
        $id = (int)($params['id'] ?? 0);
        
        if ($id <= 0) {
            return $this->responseHelper->json([
                'error' => 'Invalid product ID'
            ], 400);
        }
        
        try {
            $product = $this->productService->getProductById($id);
            
            if ($product === null) {
                return $this->responseHelper->json([
                    'error' => 'Product not found'
                ], 404);
            }
            
            return $this->responseHelper->json($product);
        } catch (\Exception $e) {
            return $this->responseHelper->json([
                'error' => 'Failed to retrieve product',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getProductsByCategory(array $params): string
    {
        $category = $params['category'] ?? '';
        $limit = (int)($_GET['limit'] ?? 10);
        
        if (empty($category)) {
            return $this->responseHelper->json([
                'error' => 'Category is required'
            ], 400);
        }
        
        try {
            $products = $this->productService->getProductsByCategory($category, $limit);
            return $this->responseHelper->json($products);
        } catch (\Exception $e) {
            return $this->responseHelper->json([
                'error' => 'Failed to retrieve products',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProduct(array $params): string
    {
        $id = (int)($params['id'] ?? 0);
        
        if ($id <= 0) {
            return $this->responseHelper->json([
                'error' => 'Invalid product ID'
            ], 400);
        }
        
        $requestData = json_decode(file_get_contents('php://input'), true);
        
        if ($requestData === null) {
            return $this->responseHelper->json([
                'error' => 'Invalid JSON data'
            ], 400);
        }
        
        try {
            $success = $this->productService->updateProduct($id, $requestData);
            
            if ($success) {
                return $this->responseHelper->json([
                    'message' => 'Product updated successfully'
                ]);
            } else {
                return $this->responseHelper->json([
                    'error' => 'Failed to update product'
                ], 500);
            }
        } catch (\Exception $e) {
            return $this->responseHelper->json([
                'error' => 'Failed to update product',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}