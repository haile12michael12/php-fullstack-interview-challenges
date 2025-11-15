import React from 'react';
import { render, screen, fireEvent } from '@testing-library/react';
import '@testing-library/jest-dom';
import PaymentForm from '../PaymentForm';

// Mock the payment service
jest.mock('../../services/paymentService', () => ({
  processPayment: jest.fn()
}));

import paymentService from '../../services/paymentService';

describe('PaymentForm', () => {
  const mockOnPaymentSuccess = jest.fn();
  const mockOnPaymentError = jest.fn();
  
  beforeEach(() => {
    jest.clearAllMocks();
  });

  it('renders prompt when no method is selected', () => {
    render(
      <PaymentForm 
        selectedMethod={null}
        onPaymentSuccess={mockOnPaymentSuccess}
        onPaymentError={mockOnPaymentError}
      />
    );
    
    expect(screen.getByText('Please select a payment method to continue')).toBeInTheDocument();
  });

  it('renders credit card form when credit card method is selected', () => {
    const creditCardMethod = { name: 'Credit Card', description: 'Pay with credit card' };
    
    render(
      <PaymentForm 
        selectedMethod={creditCardMethod}
        onPaymentSuccess={mockOnPaymentSuccess}
        onPaymentError={mockOnPaymentError}
      />
    );
    
    expect(screen.getByText('Credit Card')).toBeInTheDocument();
    expect(screen.getByText('Pay with credit card')).toBeInTheDocument();
    expect(screen.getByLabelText('Card Number')).toBeInTheDocument();
    expect(screen.getByLabelText('Expiry Date')).toBeInTheDocument();
    expect(screen.getByLabelText('CVV')).toBeInTheDocument();
    expect(screen.getByLabelText('Cardholder Name')).toBeInTheDocument();
  });

  it('renders PayPal form when PayPal method is selected', () => {
    const paypalMethod = { name: 'PayPal', description: 'Pay with PayPal' };
    
    render(
      <PaymentForm 
        selectedMethod={paypalMethod}
        onPaymentSuccess={mockOnPaymentSuccess}
        onPaymentError={mockOnPaymentError}
      />
    );
    
    expect(screen.getByText('PayPal')).toBeInTheDocument();
    expect(screen.getByText('Pay with PayPal')).toBeInTheDocument();
    expect(screen.getByLabelText('PayPal Email')).toBeInTheDocument();
  });

  it('submits payment with correct data', async () => {
    const creditCardMethod = { name: 'Credit Card', description: 'Pay with credit card' };
    const mockResult = { success: true, transaction_id: '12345', message: 'Payment successful' };
    
    paymentService.processPayment.mockResolvedValue(mockResult);
    
    render(
      <PaymentForm 
        selectedMethod={creditCardMethod}
        onPaymentSuccess={mockOnPaymentSuccess}
        onPaymentError={mockOnPaymentError}
      />
    );
    
    // Fill in form data
    fireEvent.change(screen.getByLabelText('Amount ($)'), { target: { value: '100.50' } });
    fireEvent.change(screen.getByLabelText('Card Number'), { target: { value: '1234567890123456' } });
    fireEvent.change(screen.getByLabelText('Expiry Date'), { target: { value: '12/25' } });
    fireEvent.change(screen.getByLabelText('CVV'), { target: { value: '123' } });
    fireEvent.change(screen.getByLabelText('Cardholder Name'), { target: { value: 'John Doe' } });
    
    // Submit form
    const submitButton = screen.getByRole('button', { name: 'Pay $100.50' });
    fireEvent.click(submitButton);
    
    // Check that payment service was called with correct data
    expect(paymentService.processPayment).toHaveBeenCalledWith(
      'Credit Card',
      100.50,
      {
        card_number: '1234567890123456',
        expiry_date: '12/25',
        cvv: '123',
        cardholder_name: 'John Doe'
      }
    );
    
    // Wait for success callback
    expect(await screen.findByText('Payment Successful!')).toBeInTheDocument();
    expect(mockOnPaymentSuccess).toHaveBeenCalledWith(mockResult);
  });

  it('shows error when payment fails', async () => {
    const creditCardMethod = { name: 'Credit Card', description: 'Pay with credit card' };
    paymentService.processPayment.mockRejectedValue(new Error('Payment failed'));
    
    render(
      <PaymentForm 
        selectedMethod={creditCardMethod}
        onPaymentSuccess={mockOnPaymentSuccess}
        onPaymentError={mockOnPaymentError}
      />
    );
    
    // Fill in form data
    fireEvent.change(screen.getByLabelText('Amount ($)'), { target: { value: '100.50' } });
    
    // Submit form
    const submitButton = screen.getByRole('button', { name: 'Pay $100.50' });
    fireEvent.click(submitButton);
    
    // Check that error callback was called
    expect(await screen.findByText('Payment processing failed')).toBeInTheDocument();
    expect(mockOnPaymentError).toHaveBeenCalled();
  });
});