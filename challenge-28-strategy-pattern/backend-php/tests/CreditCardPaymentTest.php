<?php

namespace Tests;

use App\Strategy\CreditCardPayment;
use PHPUnit\Framework\TestCase;

class CreditCardPaymentTest extends TestCase
{
    private CreditCardPayment $creditCardPayment;

    protected function setUp(): void
    {
        $this->creditCardPayment = new CreditCardPayment();
    }

    public function testGetName(): void
    {
        $this->assertEquals('Credit Card', $this->creditCardPayment->getName());
    }

    public function testGetDescription(): void
    {
        $this->assertEquals('Pay with credit or debit card', $this->creditCardPayment->getDescription());
    }

    public function testValidCreditCardDetails(): void
    {
        $validDetails = [
            'card_number' => '4111111111111111',
            'expiry_date' => '12/25',
            'cvv' => '123',
            'cardholder_name' => 'John Doe'
        ];

        $this->assertTrue($this->creditCardPayment->validate($validDetails));
    }

    public function testInvalidCreditCardDetailsMissingFields(): void
    {
        $invalidDetails = [
            'card_number' => '4111111111111111',
            'expiry_date' => '12/25'
            // Missing cvv and cardholder_name
        ];

        $this->assertFalse($this->creditCardPayment->validate($invalidDetails));
    }

    public function testInvalidCreditCardNumber(): void
    {
        $invalidDetails = [
            'card_number' => '123',
            'expiry_date' => '12/25',
            'cvv' => '123',
            'cardholder_name' => 'John Doe'
        ];

        $this->assertFalse($this->creditCardPayment->validate($invalidDetails));
    }

    public function testInvalidExpiryDate(): void
    {
        $invalidDetails = [
            'card_number' => '4111111111111111',
            'expiry_date' => '1225',
            'cvv' => '123',
            'cardholder_name' => 'John Doe'
        ];

        $this->assertFalse($this->creditCardPayment->validate($invalidDetails));
    }

    public function testInvalidCvv(): void
    {
        $invalidDetails = [
            'card_number' => '4111111111111111',
            'expiry_date' => '12/25',
            'cvv' => '12',
            'cardholder_name' => 'John Doe'
        ];

        $this->assertFalse($this->creditCardPayment->validate($invalidDetails));
    }

    public function testCalculateFee(): void
    {
        $amount = 100.00;
        $expectedFee = ($amount * 0.029) + 0.30; // 2.9% + $0.30
        $this->assertEquals($expectedFee, $this->creditCardPayment->calculateFee($amount));
    }

    public function testProcessPaymentSuccess(): void
    {
        $validDetails = [
            'card_number' => '4111111111111111',
            'expiry_date' => '12/25',
            'cvv' => '123',
            'cardholder_name' => 'John Doe'
        ];

        $result = $this->creditCardPayment->pay(100.00, $validDetails);
        
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('transaction_id', $result);
        $this->assertArrayHasKey('amount', $result);
        $this->assertArrayHasKey('fee', $result);
        $this->assertArrayHasKey('total', $result);
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

        $result = $this->creditCardPayment->pay(100.00, $invalidDetails);
        
        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid credit card details', $result['message']);
    }
}