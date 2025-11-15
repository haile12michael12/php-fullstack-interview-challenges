import React, { useState } from 'react'
import './App.css'
import PaymentMethods from './components/PaymentMethods'
import PaymentForm from './components/PaymentForm'
import paymentService from './services/paymentService'

function App() {
  const [selectedMethod, setSelectedMethod] = useState(null)
  const [transactions, setTransactions] = useState([])
  const [statistics, setStatistics] = useState(null)
  const [activeTab, setActiveTab] = useState('payment')

  const handleMethodSelect = (method) => {
    setSelectedMethod(method)
  }

  const handlePaymentSuccess = (result) => {
    // Refresh transactions and statistics after successful payment
    fetchTransactions()
    fetchStatistics()
  }

  const handlePaymentError = (result) => {
    console.error('Payment error:', result)
  }

  const fetchTransactions = async () => {
    try {
      const data = await paymentService.getTransactionHistory()
      setTransactions(data)
    } catch (err) {
      console.error('Error fetching transactions:', err)
    }
  }

  const fetchStatistics = async () => {
    try {
      const data = await paymentService.getTransactionStatistics()
      setStatistics(data)
    } catch (err) {
      console.error('Error fetching statistics:', err)
    }
  }

  return (
    <div className="App">
      <header className="App-header">
        <h1>Strategy Pattern - Payment Processing</h1>
      </header>
      <main>
        <div className="tabs">
          <button 
            className={activeTab === 'payment' ? 'active' : ''}
            onClick={() => setActiveTab('payment')}
          >
            Make Payment
          </button>
          <button 
            className={activeTab === 'transactions' ? 'active' : ''}
            onClick={() => {
              setActiveTab('transactions')
              fetchTransactions()
            }}
          >
            Transactions
          </button>
          <button 
            className={activeTab === 'statistics' ? 'active' : ''}
            onClick={() => {
              setActiveTab('statistics')
              fetchStatistics()
            }}
          >
            Statistics
          </button>
        </div>
        
        <div className="tab-content">
          {activeTab === 'payment' && (
            <div className="payment-section">
              <div className="payment-methods-container">
                <PaymentMethods onMethodSelect={handleMethodSelect} />
              </div>
              <div className="payment-form-container">
                <PaymentForm 
                  selectedMethod={selectedMethod}
                  onPaymentSuccess={handlePaymentSuccess}
                  onPaymentError={handlePaymentError}
                />
              </div>
            </div>
          )}
          
          {activeTab === 'transactions' && (
            <div className="transactions-section">
              <h2>Transaction History</h2>
              {transactions.length === 0 ? (
                <p>No transactions found</p>
              ) : (
                <div className="transactions-list">
                  {transactions.map((transaction, index) => (
                    <div key={index} className={`transaction-card ${transaction.success ? 'success' : 'failed'}`}>
                      <div className="transaction-header">
                        <span className="transaction-id">{transaction.transaction_id}</span>
                        <span className="transaction-status">
                          {transaction.success ? 'Success' : 'Failed'}
                        </span>
                      </div>
                      <div className="transaction-details">
                        <p><strong>Method:</strong> {transaction.payment_method}</p>
                        <p><strong>Amount:</strong> ${transaction.amount?.toFixed(2) || '0.00'}</p>
                        <p><strong>Fee:</strong> ${transaction.fee?.toFixed(2) || '0.00'}</p>
                        <p><strong>Total:</strong> ${transaction.total?.toFixed(2) || '0.00'}</p>
                        <p><strong>Timestamp:</strong> {transaction.timestamp}</p>
                        <p><strong>Message:</strong> {transaction.message}</p>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          )}
          
          {activeTab === 'statistics' && (
            <div className="statistics-section">
              <h2>Transaction Statistics</h2>
              {statistics ? (
                <div className="statistics-grid">
                  <div className="stat-card">
                    <h3>{statistics.total_transactions || 0}</h3>
                    <p>Total Transactions</p>
                  </div>
                  <div className="stat-card">
                    <h3>{statistics.successful_transactions || 0}</h3>
                    <p>Successful</p>
                  </div>
                  <div className="stat-card">
                    <h3>{statistics.failed_transactions || 0}</h3>
                    <p>Failed</p>
                  </div>
                  <div className="stat-card">
                    <h3>{statistics.success_rate ? `${statistics.success_rate.toFixed(2)}%` : '0%'}</h3>
                    <p>Success Rate</p>
                  </div>
                  <div className="stat-card">
                    <h3>${statistics.total_amount ? statistics.total_amount.toFixed(2) : '0.00'}</h3>
                    <p>Total Amount</p>
                  </div>
                  <div className="stat-card">
                    <h3>${statistics.total_fees ? statistics.total_fees.toFixed(2) : '0.00'}</h3>
                    <p>Total Fees</p>
                  </div>
                </div>
              ) : (
                <p>Loading statistics...</p>
              )}
            </div>
          )}
        </div>
      </main>
    </div>
  )
}

export default App