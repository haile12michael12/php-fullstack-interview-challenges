import axios from 'axios';

const API_BASE_URL = '/api';

class PaymentService {
  /**
   * Get available payment methods
   * @returns {Promise<Array>} List of payment methods
   */
  async getPaymentMethods() {
    try {
      const response = await axios.get(`${API_BASE_URL}/payment/methods`);
      return response.data.methods || [];
    } catch (error) {
      console.error('Error fetching payment methods:', error);
      throw error;
    }
  }

  /**
   * Process a payment
   * @param {string} method - Payment method name
   * @param {number} amount - Payment amount
   * @param {Object} details - Payment details
   * @returns {Promise<Object>} Payment result
   */
  async processPayment(method, amount, details) {
    try {
      const response = await axios.post(`${API_BASE_URL}/payment/process`, {
        method,
        amount,
        details
      });
      return response.data;
    } catch (error) {
      console.error('Error processing payment:', error);
      throw error;
    }
  }

  /**
   * Get transaction history
   * @returns {Promise<Array>} List of transactions
   */
  async getTransactionHistory() {
    try {
      const response = await axios.get(`${API_BASE_URL}/transactions`);
      return response.data.transactions || [];
    } catch (error) {
      console.error('Error fetching transaction history:', error);
      throw error;
    }
  }

  /**
   * Get transaction statistics
   * @returns {Promise<Object>} Transaction statistics
   */
  async getTransactionStatistics() {
    try {
      const response = await axios.get(`${API_BASE_URL}/statistics`);
      return response.data.statistics || {};
    } catch (error) {
      console.error('Error fetching transaction statistics:', error);
      throw error;
    }
  }

  /**
   * Validate payment details
   * @param {string} method - Payment method name
   * @param {Object} details - Payment details to validate
   * @returns {Promise<boolean>} Validation result
   */
  async validatePayment(method, details) {
    try {
      // In a real implementation, this would call a validation endpoint
      // For now, we'll simulate validation
      return Promise.resolve(true);
    } catch (error) {
      console.error('Error validating payment:', error);
      return false;
    }
  }
}

export default new PaymentService();