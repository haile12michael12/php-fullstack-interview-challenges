<?php

namespace App\Service;

use App\Context\PaymentProcessor;
use App\Context\TransactionManager;
use App\Strategy\CreditCardPayment;
use App\Strategy\PayPalPayment;
use App\Strategy\BankTransferPayment;
use App\Strategy\CryptoPayment;

/**
 * Payment service
 * 
 * Orchestrates payment processing and transaction management
 */
class PaymentService
{
    private PaymentProcessor $paymentProcessor;
    private TransactionManager $transactionManager;

    /**
     * Constructor
     *
     * @param PaymentProcessor $paymentProcessor The payment processor
     * @param TransactionManager $transactionManager The transaction manager
     */
    public function __construct(PaymentProcessor $paymentProcessor, TransactionManager $transactionManager)
    {
        $this->paymentProcessor = $paymentProcessor;
        $this->transactionManager = $transactionManager;
        
        // Register default payment strategies
        $this->registerDefaultStrategies();
    }

    /**
     * Register default payment strategies
     *
     * @return void
     */
    private function registerDefaultStrategies(): void
    {
        $this->paymentProcessor
            ->addStrategy(new CreditCardPayment())
            ->addStrategy(new PayPalPayment())
            ->addStrategy(new BankTransferPayment())
            ->addStrategy(new CryptoPayment());
    }

    /**
     * Process a payment
     *
     * @param string $paymentMethod The payment method to use
     * @param float $amount The amount to pay
     * @param array $paymentDetails The payment details
     * @return array The transaction result
     */
    public function processPayment(string $paymentMethod, float $amount, array $paymentDetails): array
    {
        try {
            // Process the payment
            $result = $this->paymentProcessor->processPaymentWithStrategy($paymentMethod, $amount, $paymentDetails);
            
            // Log the transaction
            $this->transactionManager->logTransaction($result);
            
            return $result;
        } catch (\Exception $e) {
            $errorResult = [
                'success' => false,
                'message' => $e->getMessage(),
                'payment_method' => $paymentMethod
            ];
            
            // Log the failed transaction
            $this->transactionManager->logTransaction($errorResult);
            
            return $errorResult;
        }
    }

    /**
     * Validate payment details
     *
     * @param string $paymentMethod The payment method to validate
     * @param array $paymentDetails The payment details to validate
     * @return bool True if valid, false otherwise
     */
    public function validatePayment(string $paymentMethod, array $paymentDetails): bool
    {
        $strategy = $this->paymentProcessor->getStrategyByName($paymentMethod);
        if ($strategy === null) {
            return false;
        }

        return $strategy->validate($paymentDetails);
    }

    /**
     * Calculate fees for a payment method
     *
     * @param string $paymentMethod The payment method
     * @param float $amount The amount to calculate fees for
     * @return float The fee amount
     */
    public function calculateFees(string $paymentMethod, float $amount): float
    {
        $strategy = $this->paymentProcessor->getStrategyByName($paymentMethod);
        if ($strategy === null) {
            return 0.0;
        }

        return $strategy->calculateFee($amount);
    }

    /**
     * Get available payment methods
     *
     * @return array The available payment methods
     */
    public function getPaymentMethods(): array
    {
        $strategies = $this->paymentProcessor->getStrategies();
        $methods = [];
        
        foreach ($strategies as $strategy) {
            $methods[] = [
                'name' => $strategy->getName(),
                'description' => $strategy->getDescription()
            ];
        }
        
        return $methods;
    }

    /**
     * Get transaction history
     *
     * @return array The transaction history
     */
    public function getTransactionHistory(): array
    {
        return $this->transactionManager->getTransactions();
    }

    /**
     * Get transaction statistics
     *
     * @return array The transaction statistics
     */
    public function getTransactionStatistics(): array
    {
        return $this->transactionManager->getStatistics();
    }

    /**
     * Get the payment processor
     *
     * @return PaymentProcessor The payment processor
     */
    public function getPaymentProcessor(): PaymentProcessor
    {
        return $this->paymentProcessor;
    }

    /**
     * Get the transaction manager
     *
     * @return TransactionManager The transaction manager
     */
    public function getTransactionManager(): TransactionManager
    {
        return $this->transactionManager;
    }
}