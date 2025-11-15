<?php

namespace App\Strategy;

/**
 * Payment strategy interface
 * 
 * Defines the contract for all payment strategies
 */
interface PaymentStrategyInterface
{
    /**
     * Process a payment
     *
     * @param float $amount The amount to pay
     * @param array $paymentDetails The payment details
     * @return array The transaction result
     */
    public function pay(float $amount, array $paymentDetails): array;

    /**
     * Get the name of the payment strategy
     *
     * @return string The strategy name
     */
    public function getName(): string;

    /**
     * Get the description of the payment strategy
     *
     * @return string The strategy description
     */
    public function getDescription(): string;

    /**
     * Validate payment details
     *
     * @param array $paymentDetails The payment details to validate
     * @return bool True if valid, false otherwise
     */
    public function validate(array $paymentDetails): bool;

    /**
     * Calculate fees for this payment method
     *
     * @param float $amount The amount to calculate fees for
     * @return float The fee amount
     */
    public function calculateFee(float $amount): float;
}