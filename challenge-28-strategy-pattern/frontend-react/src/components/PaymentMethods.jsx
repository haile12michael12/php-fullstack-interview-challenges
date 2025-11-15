import React, { useState, useEffect } from 'react';
import paymentService from '../services/paymentService';

const PaymentMethods = ({ onMethodSelect }) => {
  const [paymentMethods, setPaymentMethods] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchPaymentMethods();
  }, []);

  const fetchPaymentMethods = async () => {
    setLoading(true);
    setError(null);
    try {
      const methods = await paymentService.getPaymentMethods();
      setPaymentMethods(methods);
    } catch (err) {
      setError('Failed to load payment methods');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleMethodSelect = (method) => {
    onMethodSelect(method);
  };

  if (loading) {
    return <div className="payment-methods-loading">Loading payment methods...</div>;
  }

  if (error) {
    return <div className="payment-methods-error">Error: {error}</div>;
  }

  return (
    <div className="payment-methods">
      <h3>Available Payment Methods</h3>
      <div className="payment-methods-list">
        {paymentMethods.map((method) => (
          <div 
            key={method.name} 
            className="payment-method-card"
            onClick={() => handleMethodSelect(method)}
          >
            <h4>{method.name}</h4>
            <p>{method.description}</p>
          </div>
        ))}
      </div>
    </div>
  );
};

export default PaymentMethods;