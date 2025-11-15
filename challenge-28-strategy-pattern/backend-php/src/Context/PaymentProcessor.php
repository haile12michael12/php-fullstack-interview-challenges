<?php

namespace App\Context;

use App\Strategy\PaymentStrategyInterface;

/**
 * Payment processor context
 * 
 * Manages payment strategies and processes payments
 */
class PaymentProcessor
{
    private ?PaymentStrategyInterface $strategy = null;
    private array $strategies = [];

    /**
     * Set the payment strategy to use
     *
     * @param PaymentStrategyInterface $strategy The payment strategy
     * @return self
     */
    public function setStrategy(PaymentStrategyInterface $strategy): self
    {
        $this->strategy = $strategy;
        return $this;
    }

    /**
     * Get the current payment strategy
     *
     * @return PaymentStrategyInterface|null The current strategy
     */
    public function getStrategy(): ?PaymentStrategyInterface
    {
        return $this->strategy;
    }

    /**
     * Add a payment strategy
     *
     * @param PaymentStrategyInterface $strategy The payment strategy to add
     * @return self
     */
    public function addStrategy(PaymentStrategyInterface $strategy): self
    {
        $this->strategies[$strategy->getName()] = $strategy;
        return $this;
    }

    /**
     * Get all available strategies
     *
     * @return PaymentStrategyInterface[] The available strategies
     */
    public function getStrategies(): array
    {
        return $this->strategies;
    }

    /**
     * Get a strategy by name
     *
     * @param string $name The strategy name
     * @return PaymentStrategyInterface|null The strategy or null if not found
     */
    public function getStrategyByName(string $name): ?PaymentStrategyInterface
    {
        return $this->strategies[$name] ?? null;
    }

    /**
     * Process a payment using the current strategy
     *
     * @param float $amount The amount to pay
     * @param array $paymentDetails The payment details
     * @return array The transaction result
     * @throws \RuntimeException If no strategy is set
     */
    public function processPayment(float $amount, array $paymentDetails): array
    {
        if ($this->strategy === null) {
            throw new \RuntimeException('No payment strategy set');
        }

        return $this->strategy->pay($amount, $paymentDetails);
    }

    /**
     * Process a payment using a specific strategy
     *
     * @param string $strategyName The name of the strategy to use
     * @param float $amount The amount to pay
     * @param array $paymentDetails The payment details
     * @return array The transaction result
     * @throws \RuntimeException If strategy is not found
     */
    public function processPaymentWithStrategy(string $strategyName, float $amount, array $paymentDetails): array
    {
        $strategy = $this->getStrategyByName($strategyName);
        if ($strategy === null) {
            throw new \RuntimeException("Payment strategy '{$strategyName}' not found");
        }

        return $strategy->pay($amount, $paymentDetails);
    }

    /**
     * Validate payment details using the current strategy
     *
     * @param array $paymentDetails The payment details to validate
     * @return bool True if valid, false otherwise
     * @throws \RuntimeException If no strategy is set
     */
    public function validatePayment(array $paymentDetails): bool
    {
        if ($this->strategy === null) {
            throw new \RuntimeException('No payment strategy set');
        }

        return $this->strategy->validate($paymentDetails);
    }

    /**
     * Calculate fees using the current strategy
     *
     * @param float $amount The amount to calculate fees for
     * @return float The fee amount
     * @throws \RuntimeException If no strategy is set
     */
    public function calculateFees(float $amount): float
    {
        if ($this->strategy === null) {
            throw new \RuntimeException('No payment strategy set');
        }

        return $this->strategy->calculateFee($amount);
    }
}