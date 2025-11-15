<?php

namespace Tests;

use App\Context\PaymentProcessor;
use App\Context\TransactionManager;
use App\Service\PaymentService;
use PHPUnit\Framework\TestCase;

class PaymentServiceTest extends TestCase
{
    private PaymentService $paymentService;

    protected function setUp(): void
    {
        $paymentProcessor = new PaymentProcessor();
        $transactionManager = new TransactionManager('test_transactions.log');
        $this->paymentService = new PaymentService($paymentProcessor, $transactionManager);
    }

    public function testGetPaymentMethods(): void
    {
        $methods = $this->paymentService->getPaymentMethods();

        $this->assertCount(4, $methods);
        
        $methodNames = array_column($methods, 'name');
        $this->assertContains('Credit Card', $methodNames);
        $this->assertContains('PayPal', $methodNames);
        $this->assertContains('Bank Transfer', $methodNames);
        $this->assertContains('Cryptocurrency', $methodNames);
    }

    public function testProcessPaymentSuccess(): void
    {
        $validDetails = [
            'card_number' => '4111111111111111',
            'expiry_date' => '12/25',
            'cvv' => '123',
            'cardholder_name' => 'John Doe'
        ];

        $result = $this->paymentService->processPayment('Credit Card', 100.00, $validDetails);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('transaction_id', $result);
        $this->assertEquals(100.00, $result['amount']);
        $this->assertEquals('Credit Card', $result['payment_method']);
    }

    public function testProcessPaymentFailure(): void
    {
        $invalidDetails = [
            'card_number' => '123',
            'expiry_date' => '12/25',
            'cvv' => '123',
            'cardholder_name' => 'John Doe'
        ];

        $result = $this->paymentService->processPayment('Credit Card', 100.00, $invalidDetails);

        $this->assertFalse($result['success']);
        $this->assertEquals('Credit Card', $result['payment_method']);
    }

    public function testValidatePayment(): void
    {
        $validDetails = [
            'email' => 'user@example.com'
        ];

        $isValid = $this->paymentService->validatePayment('PayPal', $validDetails);
        $this->assertTrue($isValid);
    }

    public function testValidatePaymentInvalid(): void
    {
        $invalidDetails = [
            'email' => 'invalid-email'
        ];

        $isValid = $this->paymentService->validatePayment('PayPal', $invalidDetails);
        $this->assertFalse($isValid);
    }

    public function testCalculateFees(): void
    {
        $fee = $this->paymentService->calculateFees('Credit Card', 100.00);
        $expectedFee = (100.00 * 0.029) + 0.30; // 2.9% + $0.30
        $this->assertEquals($expectedFee, $fee);
    }

    public function testGetTransactionHistory(): void
    {
        // Process a payment to create a transaction
        $validDetails = [
            'card_number' => '4111111111111111',
            'expiry_date' => '12/25',
            'cvv' => '123',
            'cardholder_name' => 'John Doe'
        ];

        $this->paymentService->processPayment('Credit Card', 100.00, $validDetails);

        $transactions = $this->paymentService->getTransactionHistory();
        $this->assertNotEmpty($transactions);
        $this->assertEquals('Credit Card', $transactions[0]['payment_method']);
    }

    public function testGetTransactionStatistics(): void
    {
        // Process a successful payment
        $validDetails = [
            'card_number' => '4111111111111111',
            'expiry_date' => '12/25',
            'cvv' => '123',
            'cardholder_name' => 'John Doe'
        ];

        $this->paymentService->processPayment('Credit Card', 100.00, $validDetails);

        // Process a failed payment
        $invalidDetails = [
            'card_number' => '123',
            'expiry_date' => '12/25',
            'cvv' => '123',
            'cardholder_name' => 'John Doe'
        ];

        $this->paymentService->processPayment('Credit Card', 100.00, $invalidDetails);

        $stats = $this->paymentService->getTransactionStatistics();
        
        $this->assertEquals(2, $stats['total_transactions']);
        $this->assertEquals(1, $stats['successful_transactions']);
        $this->assertEquals(1, $stats['failed_transactions']);
        $this->assertEquals(50.0, $stats['success_rate']);
        $this->assertEquals(100.00, $stats['total_amount']);
    }
}