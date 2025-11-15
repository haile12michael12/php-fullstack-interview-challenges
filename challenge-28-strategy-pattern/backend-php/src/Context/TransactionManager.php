<?php

namespace App\Context;

/**
 * Transaction manager
 * 
 * Manages transaction history and logging
 */
class TransactionManager
{
    private array $transactions = [];
    private string $logFile;

    /**
     * Constructor
     *
     * @param string $logFile The path to the transaction log file
     */
    public function __construct(string $logFile = 'transactions.log')
    {
        $this->logFile = $logFile;
    }

    /**
     * Log a transaction
     *
     * @param array $transactionData The transaction data
     * @return self
     */
    public function logTransaction(array $transactionData): self
    {
        $transactionData['timestamp'] = date('Y-m-d H:i:s');
        $this->transactions[] = $transactionData;
        
        // Log to file
        $logEntry = json_encode($transactionData) . PHP_EOL;
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
        
        return $this;
    }

    /**
     * Get all transactions
     *
     * @return array The transaction history
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * Get transactions by payment method
     *
     * @param string $paymentMethod The payment method to filter by
     * @return array The filtered transactions
     */
    public function getTransactionsByMethod(string $paymentMethod): array
    {
        return array_filter($this->transactions, function($transaction) use ($paymentMethod) {
            return isset($transaction['payment_method']) && $transaction['payment_method'] === $paymentMethod;
        });
    }

    /**
     * Get successful transactions
     *
     * @return array The successful transactions
     */
    public function getSuccessfulTransactions(): array
    {
        return array_filter($this->transactions, function($transaction) {
            return isset($transaction['success']) && $transaction['success'] === true;
        });
    }

    /**
     * Get failed transactions
     *
     * @return array The failed transactions
     */
    public function getFailedTransactions(): array
    {
        return array_filter($this->transactions, function($transaction) {
            return isset($transaction['success']) && $transaction['success'] === false;
        });
    }

    /**
     * Get transaction statistics
     *
     * @return array The transaction statistics
     */
    public function getStatistics(): array
    {
        $totalTransactions = count($this->transactions);
        $successfulTransactions = count($this->getSuccessfulTransactions());
        $failedTransactions = count($this->getFailedTransactions());
        
        $totalAmount = 0;
        $totalFees = 0;
        
        foreach ($this->getSuccessfulTransactions() as $transaction) {
            $totalAmount += $transaction['amount'] ?? 0;
            $totalFees += $transaction['fee'] ?? 0;
        }
        
        return [
            'total_transactions' => $totalTransactions,
            'successful_transactions' => $successfulTransactions,
            'failed_transactions' => $failedTransactions,
            'success_rate' => $totalTransactions > 0 ? ($successfulTransactions / $totalTransactions) * 100 : 0,
            'total_amount' => $totalAmount,
            'total_fees' => $totalFees
        ];
    }

    /**
     * Clear transaction history
     *
     * @return self
     */
    public function clearHistory(): self
    {
        $this->transactions = [];
        if (file_exists($this->logFile)) {
            file_put_contents($this->logFile, '');
        }
        return $this;
    }
}