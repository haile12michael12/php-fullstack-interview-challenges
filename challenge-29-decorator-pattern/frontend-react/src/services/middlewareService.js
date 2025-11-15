import axios from 'axios';

const API_BASE_URL = '/api';

class MiddlewareService {
  /**
   * Get available middleware
   * @returns {Promise<Array>} List of available middleware
   */
  async getAvailableMiddleware() {
    try {
      const response = await axios.get(`${API_BASE_URL}/middleware/list`);
      return response.data.middleware || [];
    } catch (error) {
      console.error('Error fetching middleware list:', error);
      throw error;
    }
  }

  /**
   * Get pipeline configuration
   * @returns {Promise<Object>} Pipeline configuration
   */
  async getPipelineConfig() {
    try {
      const response = await axios.get(`${API_BASE_URL}/pipeline/config`);
      return response.data.config || {};
    } catch (error) {
      console.error('Error fetching pipeline config:', error);
      throw error;
    }
  }

  /**
   * Apply middleware to a request
   * @param {Array} middlewareList - List of middleware to apply
   * @returns {Promise<Object>} Applied middleware details
   */
  async applyMiddleware(middlewareList) {
    try {
      const response = await axios.post(`${API_BASE_URL}/middleware/apply`, {
        middleware: middlewareList
      });
      return response.data.applied_middleware || [];
    } catch (error) {
      console.error('Error applying middleware:', error);
      throw error;
    }
  }

  /**
   * Process a request through the middleware pipeline
   * @param {Array} middlewareList - List of middleware to apply
   * @param {Object} requestData - Request data to process
   * @returns {Promise<Object>} Processed response
   */
  async processRequest(middlewareList, requestData) {
    try {
      const response = await axios.post(`${API_BASE_URL}/request/process`, {
        middleware: middlewareList,
        request: requestData
      });
      return response.data.response || {};
    } catch (error) {
      console.error('Error processing request:', error);
      throw error;
    }
  }

  /**
   * Get middleware metrics
   * @returns {Promise<Object>} Middleware metrics
   */
  async getMiddlewareMetrics() {
    try {
      const response = await axios.get(`${API_BASE_URL}/metrics/middleware`);
      return response.data.metrics || {};
    } catch (error) {
      console.error('Error fetching middleware metrics:', error);
      throw error;
    }
  }
}

export default new MiddlewareService();