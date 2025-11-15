<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Context\PaymentProcessor;
use App\Context\TransactionManager;
use App\Service\PaymentService;
use App\Strategy\CreditCardPayment;
use App\Strategy\PayPalPayment;
use App\Strategy\BankTransferPayment;
use App\Strategy\CryptoPayment;

echo "=== Strategy Pattern Demo ===\n\n";

// Create components
$paymentProcessor = new PaymentProcessor();
$transactionManager = new TransactionManager('test_transactions.log');
$paymentService = new PaymentService($paymentProcessor, $transactionManager);

// Get available payment methods
echo "1. Available Payment Methods:\n";
$methods = $paymentService->getPaymentMethods();
foreach ($methods as $method) {
    echo "   - {$method['name']}: {$method['description']}\n";
}
echo "\n";

// Test Credit Card Payment
echo "2. Testing Credit Card Payment:\n";
$ccResult = $paymentService->processPayment('Credit Card', 100.00, [
    'card_number' => '4111111111111111',
    'expiry_date' => '12/25',
    'cvv' => '123',
    'cardholder_name' => 'John Doe'
]);
echo "   Result: " . ($ccResult['success'] ? 'SUCCESS' : 'FAILED') . "\n";
if ($ccResult['success']) {
    echo "   Transaction ID: {$ccResult['transaction_id']}\n";
    echo "   Amount: $" . number_format($ccResult['amount'], 2) . "\n";
    echo "   Fee: $" . number_format($ccResult['fee'], 2) . "\n";
    echo "   Total: $" . number_format($ccResult['total'], 2) . "\n";
}
echo "\n";

// Test PayPal Payment
echo "3. Testing PayPal Payment:\n";
$ppResult = $paymentService->processPayment('PayPal', 50.00, [
    'email' => 'user@example.com'
]);
echo "   Result: " . ($ppResult['success'] ? 'SUCCESS' : 'FAILED') . "\n";
if ($ppResult['success']) {
    echo "   Transaction ID: {$ppResult['transaction_id']}\n";
    echo "   Amount: $" . number_format($ppResult['amount'], 2) . "\n";
    echo "   Fee: $" . number_format($ppResult['fee'], 2) . "\n";
    echo "   Total: $" . number_format($ppResult['total'], 2) . "\n";
}
echo "\n";

// Test Bank Transfer Payment
echo "4. Testing Bank Transfer Payment:\n";
$btResult = $paymentService->processPayment('Bank Transfer', 200.00, [
    'account_number' => '1234567890',
    'routing_number' => '123456789',
    'account_holder_name' => 'John Doe'
]);
echo "   Result: " . ($btResult['success'] ? 'SUCCESS' : 'FAILED') . "\n";
if ($btResult['success']) {
    echo "   Transaction ID: {$btResult['transaction_id']}\n";
    echo "   Amount: $" . number_format($btResult['amount'], 2) . "\n";
    echo "   Fee: $" . number_format($btResult['fee'], 2) . "\n";
    echo "   Total: $" . number_format($btResult['total'], 2) . "\n";
}
echo "\n";

// Test Cryptocurrency Payment
echo "5. Testing Cryptocurrency Payment:\n";
$cryptoResult = $paymentService->processPayment('Cryptocurrency', 75.00, [
    'wallet_address' => '1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa',
    'currency' => 'BTC'
]);
echo "   Result: " . ($cryptoResult['success'] ? 'SUCCESS' : 'FAILED') . "\n";
if ($cryptoResult['success']) {
    echo "   Transaction ID: {$cryptoResult['transaction_id']}\n";
    echo "   Amount: $" . number_format($cryptoResult['amount'], 2) . "\n";
    echo "   Fee: $" . number_format($cryptoResult['fee'], 2) . "\n";
    echo "   Total: $" . number_format($cryptoResult['total'], 2) . "\n";
}
echo "\n";

// Test Fee Calculations
echo "6. Fee Calculations for $100.00:\n";
foreach ($methods as $method) {
    $fee = $paymentService->calculateFees($method['name'], 100.00);
    echo "   {$method['name']}: $" . number_format($fee, 2) . "\n";
}
echo "\n";

// Show transaction statistics
echo "7. Transaction Statistics:\n";
$stats = $paymentService->getTransactionStatistics();
echo "   Total Transactions: {$stats['total_transactions']}\n";
echo "   Successful Transactions: {$stats['successful_transactions']}\n";
echo "   Failed Transactions: {$stats['failed_transactions']}\n";
echo "   Success Rate: " . number_format($stats['success_rate'], 2) . "%\n";
echo "   Total Amount Processed: $" . number_format($stats['total_amount'], 2) . "\n";
echo "   Total Fees Collected: $" . number_format($stats['total_fees'], 2) . "\n";
echo "\n";

// Show transaction history
echo "8. Recent Transactions:\n";
$transactions = $paymentService->getTransactionHistory();
foreach (array_slice($transactions, 0, 3) as $transaction) {
    $status = $transaction['success'] ? 'SUCCESS' : 'FAILED';
    $amount = $transaction['amount'] ?? 0;
    echo "   [{$transaction['timestamp']}] {$status} - {$transaction['payment_method']} - $" . number_format($amount, 2) . "\n";
}

echo "\n=== Demo Complete ===\n";