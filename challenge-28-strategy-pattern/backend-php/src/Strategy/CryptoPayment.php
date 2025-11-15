<?php

namespace App\Strategy;

/**
 * Cryptocurrency payment strategy
 * 
 * Implements payment processing using cryptocurrency
 */
class CryptoPayment implements PaymentStrategyInterface
{
    /**
     * Process a cryptocurrency payment
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
                'message' => 'Invalid cryptocurrency details'
            ];
        }

        // Simulate payment processing
        $fee = $this->calculateFee($amount);
        $totalAmount = $amount + $fee;
        
        // In a real implementation, this would interact with a cryptocurrency wallet
        $transactionId = uniqid('crypto_');
        
        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'fee' => $fee,
            'total' => $totalAmount,
            'payment_method' => $this->getName(),
            'message' => 'Cryptocurrency payment initiated. Please send the required amount to the provided wallet address.'
        ];
    }

    /**
     * Get the name of the payment strategy
     *
     * @return string The strategy name
     */
    public function getName(): string
    {
        return 'Cryptocurrency';
    }

    /**
     * Get the description of the payment strategy
     *
     * @return string The strategy description
     */
    public function getDescription(): string
    {
        return 'Pay with Bitcoin, Ethereum, or other cryptocurrencies';
    }

    /**
     * Validate cryptocurrency payment details
     *
     * @param array $paymentDetails The payment details to validate
     * @return bool True if valid, false otherwise
     */
    public function validate(array $paymentDetails): bool
    {
        // Check required fields
        $requiredFields = ['wallet_address', 'currency'];
        foreach ($requiredFields as $field) {
            if (!isset($paymentDetails[$field]) || empty($paymentDetails[$field])) {
                return false;
            }
        }

        // Validate wallet address format (simplified)
        if (strlen($paymentDetails['wallet_address']) < 26 || strlen($paymentDetails['wallet_address']) > 42) {
            return false;
        }

        // Validate currency (simplified)
        $supportedCurrencies = ['BTC', 'ETH', 'LTC', 'BCH'];
        if (!in_array(strtoupper($paymentDetails['currency']), $supportedCurrencies)) {
            return false;
        }

        return true;
    }

    /**
     * Calculate fees for cryptocurrency payment (1%)
     *
     * @param float $amount The amount to calculate fees for
     * @return float The fee amount
     */
    public function calculateFee(float $amount): float
    {
        return $amount * 0.01;
    }
}