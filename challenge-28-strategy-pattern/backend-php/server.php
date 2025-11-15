<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Context\PaymentProcessor;
use App\Context\TransactionManager;
use App\Service\PaymentService;

// Create components
$paymentProcessor = new PaymentProcessor();
$transactionManager = new TransactionManager('server_transactions.log');
$paymentService = new PaymentService($paymentProcessor, $transactionManager);

echo "Strategy Pattern Payment Server\n";
echo "=============================\n";
echo "Available endpoints:\n";
echo "  GET  /api/payment/methods     - List available payment methods\n";
echo "  POST /api/payment/process     - Process payment\n";
echo "  GET  /api/transactions        - Get transaction history\n";
echo "  GET  /api/statistics          - Get transaction statistics\n";
echo "\n";
echo "Server running on http://localhost:8000\n";
echo "Press Ctrl+C to stop\n\n";

// Simple HTTP server implementation
while (true) {
    // In a real implementation, you would use a proper web framework
    // This is just a demonstration of the service capabilities
    
    // Simulate some payments for demonstration
    if (rand(1, 10) == 1) {
        $methods = $paymentService->getPaymentMethods();
        $randomMethod = $methods[array_rand($methods)];
        
        $testPayments = [
            'Credit Card' => [
                'card_number' => '4111111111111111',
                'expiry_date' => '12/25',
                'cvv' => '123',
                'cardholder_name' => 'John Doe'
            ],
            'PayPal' => [
                'email' => 'user@example.com'
            ],
            'Bank Transfer' => [
                'account_number' => '1234567890',
                'routing_number' => '123456789',
                'account_holder_name' => 'John Doe'
            ],
            'Cryptocurrency' => [
                'wallet_address' => '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa',
                'currency' => 'BTC'
            ]
        ];
        
        $amount = rand(10, 1000) / 10; // Random amount between $1 and $100
        $paymentDetails = $testPayments[$randomMethod['name']] ?? [];
        
        $result = $paymentService->processPayment($randomMethod['name'], $amount, $paymentDetails);
        
        $status = $result['success'] ? 'SUCCESS' : 'FAILED';
        echo "[" . date('Y-m-d H:i:s') . "] {$status} - {$randomMethod['name']} - $" . number_format($amount, 2) . "\n";
    }
    
    sleep(5);
}