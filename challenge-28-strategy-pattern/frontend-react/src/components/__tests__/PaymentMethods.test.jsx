import React from 'react';
import { render, screen, fireEvent } from '@testing-library/react';
import '@testing-library/jest-dom';
import PaymentMethods from '../PaymentMethods';

// Mock the payment service
jest.mock('../../services/paymentService', () => ({
  getPaymentMethods: jest.fn()
}));

import paymentService from '../../services/paymentService';

describe('PaymentMethods', () => {
  const mockOnMethodSelect = jest.fn();
  
  beforeEach(() => {
    jest.clearAllMocks();
  });

  it('renders loading state initially', () => {
    paymentService.getPaymentMethods.mockResolvedValue([]);
    render(<PaymentMethods onMethodSelect={mockOnMethodSelect} />);
    
    expect(screen.getByText('Loading payment methods...')).toBeInTheDocument();
  });

  it('renders payment methods when loaded', async () => {
    const mockMethods = [
      { name: 'Credit Card', description: 'Pay with credit card' },
      { name: 'PayPal', description: 'Pay with PayPal' }
    ];
    
    paymentService.getPaymentMethods.mockResolvedValue(mockMethods);
    
    render(<PaymentMethods onMethodSelect={mockOnMethodSelect} />);
    
    // Wait for async loading to complete
    const methodCards = await screen.findAllByText(/Pay/);
    expect(methodCards).toHaveLength(2);
    
    expect(screen.getByText('Credit Card')).toBeInTheDocument();
    expect(screen.getByText('Pay with credit card')).toBeInTheDocument();
    expect(screen.getByText('PayPal')).toBeInTheDocument();
    expect(screen.getByText('Pay with PayPal')).toBeInTheDocument();
  });

  it('calls onMethodSelect when a method is clicked', async () => {
    const mockMethods = [
      { name: 'Credit Card', description: 'Pay with credit card' }
    ];
    
    paymentService.getPaymentMethods.mockResolvedValue(mockMethods);
    
    render(<PaymentMethods onMethodSelect={mockOnMethodSelect} />);
    
    const methodCard = await screen.findByText('Credit Card');
    fireEvent.click(methodCard);
    
    expect(mockOnMethodSelect).toHaveBeenCalledWith(mockMethods[0]);
  });

  it('renders error state when loading fails', async () => {
    paymentService.getPaymentMethods.mockRejectedValue(new Error('Failed to load'));
    
    render(<PaymentMethods onMethodSelect={mockOnMethodSelect} />);
    
    const errorElement = await screen.findByText(/Error:/);
    expect(errorElement).toBeInTheDocument();
    expect(errorElement).toHaveTextContent('Error: Failed to load payment methods');
  });
});