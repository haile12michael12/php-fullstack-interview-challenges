import React, { useState } from 'react';
import paymentService from '../services/paymentService';

const PaymentForm = ({ selectedMethod, onPaymentSuccess, onPaymentError }) => {
  const [amount, setAmount] = useState('');
  const [paymentDetails, setPaymentDetails] = useState({});
  const [isProcessing, setIsProcessing] = useState(false);
  const [result, setResult] = useState(null);
  const [error, setError] = useState(null);

  // Reset form when method changes
  React.useEffect(() => {
    setAmount('');
    setPaymentDetails({});
    setResult(null);
    setError(null);
  }, [selectedMethod]);

  // Handle input changes based on payment method
  const handleDetailChange = (field, value) => {
    setPaymentDetails(prev => ({
      ...prev,
      [field]: value
    }));
  };

  // Render payment method specific fields
  const renderPaymentFields = () => {
    if (!selectedMethod) return null;

    switch (selectedMethod.name) {
      case 'Credit Card':
        return (
          <div className="payment-fields">
            <div className="form-group">
              <label htmlFor="cardNumber">Card Number</label>
              <input
                type="text"
                id="cardNumber"
                placeholder="1234 5678 9012 3456"
                value={paymentDetails.card_number || ''}
                onChange={(e) => handleDetailChange('card_number', e.target.value)}
                required
              />
            </div>
            <div className="form-row">
              <div className="form-group">
                <label htmlFor="expiryDate">Expiry Date</label>
                <input
                  type="text"
                  id="expiryDate"
                  placeholder="MM/YY"
                  value={paymentDetails.expiry_date || ''}
                  onChange={(e) => handleDetailChange('expiry_date', e.target.value)}
                  required
                />
              </div>
              <div className="form-group">
                <label htmlFor="cvv">CVV</label>
                <input
                  type="text"
                  id="cvv"
                  placeholder="123"
                  value={paymentDetails.cvv || ''}
                  onChange={(e) => handleDetailChange('cvv', e.target.value)}
                  required
                />
              </div>
            </div>
            <div className="form-group">
              <label htmlFor="cardholderName">Cardholder Name</label>
              <input
                type="text"
                id="cardholderName"
                placeholder="John Doe"
                value={paymentDetails.cardholder_name || ''}
                onChange={(e) => handleDetailChange('cardholder_name', e.target.value)}
                required
              />
            </div>
          </div>
        );
      
      case 'PayPal':
        return (
          <div className="payment-fields">
            <div className="form-group">
              <label htmlFor="email">PayPal Email</label>
              <input
                type="email"
                id="email"
                placeholder="user@example.com"
                value={paymentDetails.email || ''}
                onChange={(e) => handleDetailChange('email', e.target.value)}
                required
              />
            </div>
          </div>
        );
      
      case 'Bank Transfer':
        return (
          <div className="payment-fields">
            <div className="form-group">
              <label htmlFor="accountNumber">Account Number</label>
              <input
                type="text"
                id="accountNumber"
                placeholder="1234567890"
                value={paymentDetails.account_number || ''}
                onChange={(e) => handleDetailChange('account_number', e.target.value)}
                required
              />
            </div>
            <div className="form-group">
              <label htmlFor="routingNumber">Routing Number</label>
              <input
                type="text"
                id="routingNumber"
                placeholder="123456789"
                value={paymentDetails.routing_number || ''}
                onChange={(e) => handleDetailChange('routing_number', e.target.value)}
                required
              />
            </div>
            <div className="form-group">
              <label htmlFor="accountHolderName">Account Holder Name</label>
              <input
                type="text"
                id="accountHolderName"
                placeholder="John Doe"
                value={paymentDetails.account_holder_name || ''}
                onChange={(e) => handleDetailChange('account_holder_name', e.target.value)}
                required
              />
            </div>
          </div>
        );
      
      case 'Cryptocurrency':
        return (
          <div className="payment-fields">
            <div className="form-group">
              <label htmlFor="walletAddress">Wallet Address</label>
              <input
                type="text"
                id="walletAddress"
                placeholder="Wallet address"
                value={paymentDetails.wallet_address || ''}
                onChange={(e) => handleDetailChange('wallet_address', e.target.value)}
                required
              />
            </div>
            <div className="form-group">
              <label htmlFor="currency">Currency</label>
              <select
                id="currency"
                value={paymentDetails.currency || ''}
                onChange={(e) => handleDetailChange('currency', e.target.value)}
                required
              >
                <option value="">Select Currency</option>
                <option value="BTC">Bitcoin (BTC)</option>
                <option value="ETH">Ethereum (ETH)</option>
                <option value="LTC">Litecoin (LTC)</option>
                <option value="BCH">Bitcoin Cash (BCH)</option>
              </select>
            </div>
          </div>
        );
      
      default:
        return (
          <div className="payment-fields">
            <p>Unsupported payment method</p>
          </div>
        );
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!selectedMethod || !amount) return;

    setIsProcessing(true);
    setError(null);
    setResult(null);

    try {
      const result = await paymentService.processPayment(
        selectedMethod.name,
        parseFloat(amount),
        paymentDetails
      );
      
      setResult(result);
      if (result.success) {
        onPaymentSuccess && onPaymentSuccess(result);
      } else {
        onPaymentError && onPaymentError(result);
      }
    } catch (err) {
      setError('Payment processing failed');
      onPaymentError && onPaymentError({ success: false, message: err.message });
    } finally {
      setIsProcessing(false);
    }
  };

  if (!selectedMethod) {
    return (
      <div className="payment-form">
        <p>Please select a payment method to continue</p>
      </div>
    );
  }

  return (
    <div className="payment-form">
      <h3>Complete Your Payment</h3>
      <div className="selected-method">
        <h4>{selectedMethod.name}</h4>
        <p>{selectedMethod.description}</p>
      </div>
      
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label htmlFor="amount">Amount ($)</label>
          <input
            type="number"
            id="amount"
            step="0.01"
            min="0.01"
            placeholder="0.00"
            value={amount}
            onChange={(e) => setAmount(e.target.value)}
            required
          />
        </div>
        
        {renderPaymentFields()}
        
        {error && <div className="error-message">{error}</div>}
        
        {result && (
          <div className={`result-message ${result.success ? 'success' : 'error'}`}>
            <h4>{result.success ? 'Payment Successful!' : 'Payment Failed'}</h4>
            <p>{result.message}</p>
            {result.transaction_id && <p>Transaction ID: {result.transaction_id}</p>}
            {result.total && <p>Total: ${result.total.toFixed(2)}</p>}
          </div>
        )}
        
        <button 
          type="submit" 
          disabled={isProcessing || !amount}
          className="pay-button"
        >
          {isProcessing ? 'Processing...' : `Pay $${amount || '0.00'}`}
        </button>
      </form>
    </div>
  );
};

export default PaymentForm;