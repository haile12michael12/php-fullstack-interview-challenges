<?php

namespace App\Strategy;

/**
 * PayPal payment strategy
 * 
 * Implements payment processing using PayPal
 */
class PayPalPayment implements PaymentStrategyInterface
{
    /**
     * Process a PayPal payment
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
                'message' => 'Invalid PayPal details'
            ];
        }

        // Simulate payment processing
        $fee = $this->calculateFee($amount);
        $totalAmount = $amount + $fee;
        
        // In a real implementation, this would call PayPal's API
        $transactionId = uniqid('pp_');
        
        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'fee' => $fee,
            'total' => $totalAmount,
            'payment_method' => $this->getName(),
            'message' => 'PayPal payment processed successfully'
        ];
    }

    /**
     * Get the name of the payment strategy
     *
     * @return string The strategy name
     */
    public function getName(): string
    {
        return 'PayPal';
    }

    /**
     * Get the description of the payment strategy
     *
     * @return string The strategy description
     */
    public function getDescription(): string
    {
        return 'Pay with your PayPal account';
    }

    /**
     * Validate PayPal payment details
     *
     * @param array $paymentDetails The payment details to validate
     * @return bool True if valid, false otherwise
     */
    public function validate(array $paymentDetails): bool
    {
        // Check required fields
        $requiredFields = ['email'];
        foreach ($requiredFields as $field) {
            if (!isset($paymentDetails[$field]) || empty($paymentDetails[$field])) {
                return false;
            }
        }

        // Validate email format
        if (!filter_var($paymentDetails['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    /**
     * Calculate fees for PayPal payment (3.49% + $0.49)
     *
     * @param float $amount The amount to calculate fees for
     * @return float The fee amount
     */
    public function calculateFee(float $amount): float
    {
        return ($amount * 0.0349) + 0.49;
    }
}