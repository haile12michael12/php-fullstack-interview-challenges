<?php

namespace App\Strategy;

/**
 * Bank transfer payment strategy
 * 
 * Implements payment processing using bank transfer
 */
class BankTransferPayment implements PaymentStrategyInterface
{
    /**
     * Process a bank transfer payment
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
                'message' => 'Invalid bank transfer details'
            ];
        }

        // Simulate payment processing
        $fee = $this->calculateFee($amount);
        $totalAmount = $amount + $fee;
        
        // In a real implementation, this would initiate a bank transfer
        $transactionId = uniqid('bt_');
        
        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'fee' => $fee,
            'total' => $totalAmount,
            'payment_method' => $this->getName(),
            'message' => 'Bank transfer initiated successfully. Please complete the transfer within 24 hours.'
        ];
    }

    /**
     * Get the name of the payment strategy
     *
     * @return string The strategy name
     */
    public function getName(): string
    {
        return 'Bank Transfer';
    }

    /**
     * Get the description of the payment strategy
     *
     * @return string The strategy description
     */
    public function getDescription(): string
    {
        return 'Pay directly from your bank account';
    }

    /**
     * Validate bank transfer payment details
     *
     * @param array $paymentDetails The payment details to validate
     * @return bool True if valid, false otherwise
     */
    public function validate(array $paymentDetails): bool
    {
        // Check required fields
        $requiredFields = ['account_number', 'routing_number', 'account_holder_name'];
        foreach ($requiredFields as $field) {
            if (!isset($paymentDetails[$field]) || empty($paymentDetails[$field])) {
                return false;
            }
        }

        // Validate account number (simplified)
        if (!is_numeric($paymentDetails['account_number']) || strlen($paymentDetails['account_number']) < 5 || strlen($paymentDetails['account_number']) > 17) {
            return false;
        }

        // Validate routing number (9 digits)
        if (!is_numeric($paymentDetails['routing_number']) || strlen($paymentDetails['routing_number']) != 9) {
            return false;
        }

        return true;
    }

    /**
     * Calculate fees for bank transfer payment (0.5%)
     *
     * @param float $amount The amount to calculate fees for
     * @return float The fee amount
     */
    public function calculateFee(float $amount): float
    {
        return $amount * 0.005;
    }
}