<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Context\PaymentProcessor;
use App\Context\TransactionManager;
use App\Service\PaymentService;

// Create components
$paymentProcessor = new PaymentProcessor();
$transactionManager = new TransactionManager('transactions.log');
$paymentService = new PaymentService($paymentProcessor, $transactionManager);

// Handle HTTP requests
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

header('Content-Type: application/json');

switch ($uri) {
    case '/':
        echo json_encode([
            'message' => 'Strategy Pattern Payment API',
            'endpoints' => [
                'GET /api/payment/methods' => 'List available payment methods',
                'POST /api/payment/process' => 'Process payment with selected strategy',
                'GET /api/transactions' => 'Get transaction history',
                'GET /api/statistics' => 'Get transaction statistics'
            ]
        ]);
        break;
        
    case '/api/payment/methods':
        if ($method === 'GET') {
            echo json_encode([
                'methods' => $paymentService->getPaymentMethods()
            ]);
        }
        break;
        
    case '/api/payment/process':
        if ($method === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $paymentMethod = $input['method'] ?? '';
            $amount = $input['amount'] ?? 0;
            $details = $input['details'] ?? [];
            
            $result = $paymentService->processPayment($paymentMethod, $amount, $details);
            
            http_response_code($result['success'] ? 200 : 400);
            echo json_encode($result);
        }
        break;
        
    case '/api/transactions':
        if ($method === 'GET') {
            echo json_encode([
                'transactions' => $paymentService->getTransactionHistory()
            ]);
        }
        break;
        
    case '/api/statistics':
        if ($method === 'GET') {
            echo json_encode([
                'statistics' => $paymentService->getTransactionStatistics()
            ]);
        }
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
        break;
}