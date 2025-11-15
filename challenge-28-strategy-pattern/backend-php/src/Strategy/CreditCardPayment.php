<?php

namespace App\Strategy;

/**
 * Credit card payment strategy
 * 
 * Implements payment processing using credit card
 */
class CreditCardPayment implements PaymentStrategyInterface
{
    /**
     * Process a credit card payment
     *
     * @param float $amount The amount to pay
     * @param array $paymentDetails The payment details
     * @return array The transaction result
     */
    public function pay(float $amount, array $paymentDetails): array
    {
        // Validate payment details
        if (!$this->validate($paymentDetails)) {
            return [
                'success' => false,
                'message' => 'Invalid credit card details'
            ];
        }

        // Simulate payment processing
        $fee = $this->calculateFee($amount);
        $totalAmount = $amount + $fee;
        
        // In a real implementation, this would call a payment gateway API
        $transactionId = uniqid('cc_');
        
        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'fee' => $fee,
            'total' => $totalAmount,
            'payment_method' => $this->getName(),
            'message' => 'Credit card payment processed successfully'
        ];
    }

    /**
     * Get the name of the payment strategy
     *
     * @return string The strategy name
     */
    public function getName(): string
    {
        return 'Credit Card';
    }

    /**
     * Get the description of the payment strategy
     *
     * @return string The strategy description
     */
    public function getDescription(): string
    {
        return 'Pay with credit or debit card';
    }

    /**
     * Validate credit card payment details
     *
     * @param array $paymentDetails The payment details to validate
     * @return bool True if valid, false otherwise
     */
    public function validate(array $paymentDetails): bool
    {
        // Check required fields
        $requiredFields = ['card_number', 'expiry_date', 'cvv', 'cardholder_name'];
        foreach ($requiredFields as $field) {
            if (!isset($paymentDetails[$field]) || empty($paymentDetails[$field])) {
                return false;
            }
        }

        // Validate card number (simplified)
        $cardNumber = preg_replace('/\s+/', '', $paymentDetails['card_number']);
        if (!is_numeric($cardNumber) || strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
            return false;
        }

        // Validate expiry date (MM/YY format)
        if (!preg_match('/^\d{2}\/\d{2}$/', $paymentDetails['expiry_date'])) {
            return false;
        }

        // Validate CVV (3 or 4 digits)
        if (!preg_match('/^\d{3,4}$/', $paymentDetails['cvv'])) {
            return false;
        }

        return true;
    }

    /**
     * Calculate fees for credit card payment (2.9% + $0.30)
     *
     * @param float $amount The amount to calculate fees for
     * @return float The fee amount
     */
    public function calculateFee(float $amount): float
    {
        return ($amount * 0.029) + 0.30;
    }
}