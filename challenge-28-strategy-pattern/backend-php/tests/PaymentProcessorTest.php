<?php

namespace Tests;

use App\Context\PaymentProcessor;
use App\Strategy\CreditCardPayment;
use App\Strategy\PayPalPayment;
use PHPUnit\Framework\TestCase;

class PaymentProcessorTest extends TestCase
{
    private PaymentProcessor $paymentProcessor;

    protected function setUp(): void
    {
        $this->paymentProcessor = new PaymentProcessor();
    }

    public function testAddAndGetStrategy(): void
    {
        $creditCardStrategy = new CreditCardPayment();
        $this->paymentProcessor->addStrategy($creditCardStrategy);

        $this->assertCount(1, $this->paymentProcessor->getStrategies());
        $this->assertSame($creditCardStrategy, $this->paymentProcessor->getStrategyByName('Credit Card'));
    }

    public function testSetAndGetStrategy(): void
    {
        $creditCardStrategy = new CreditCardPayment();
        $this->paymentProcessor->setStrategy($creditCardStrategy);

        $this->assertSame($creditCardStrategy, $this->paymentProcessor->getStrategy());
    }

    public function testProcessPaymentWithStrategy(): void
    {
        $creditCardStrategy = new CreditCardPayment();
        $this->paymentProcessor->addStrategy($creditCardStrategy);

        $validDetails = [
            'card_number' => '4111111111111111',
            'expiry_date' => '12/25',
            'cvv' => '123',
            'cardholder_name' => 'John Doe'
        ];

        $result = $this->paymentProcessor->processPaymentWithStrategy('Credit Card', 100.00, $validDetails);

        $this->assertTrue($result['success']);
        $this->assertEquals(100.00, $result['amount']);
    }

    public function testProcessPaymentWithStrategyNotFound(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("Payment strategy 'Nonexistent Strategy' not found");

        $this->paymentProcessor->processPaymentWithStrategy('Nonexistent Strategy', 100.00, []);
    }

    public function testProcessPaymentWithoutStrategy(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No payment strategy set');

        $this->paymentProcessor->processPayment(100.00, []);
    }

    public function testValidatePaymentWithoutStrategy(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No payment strategy set');

        $this->paymentProcessor->validatePayment([]);
    }

    public function testCalculateFeesWithoutStrategy(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No payment strategy set');

        $this->paymentProcessor->calculateFees(100.00);
    }

    public function testMultipleStrategies(): void
    {
        $creditCardStrategy = new CreditCardPayment();
        $paypalStrategy = new PayPalPayment();

        $this->paymentProcessor->addStrategy($creditCardStrategy);
        $this->paymentProcessor->addStrategy($paypalStrategy);

        $this->assertCount(2, $this->paymentProcessor->getStrategies());
        $this->assertNotNull($this->paymentProcessor->getStrategyByName('Credit Card'));
        $this->assertNotNull($this->paymentProcessor->getStrategyByName('PayPal'));
    }
}