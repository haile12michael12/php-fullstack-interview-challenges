import axios from 'axios';

const API_BASE_URL = '/api/memory';

class MemoryService {
  constructor() {
    this.api = axios.create({
      baseURL: API_BASE_URL,
      headers: {
        'Content-Type': 'application/json',
      },
    });
  }
  
  async getProfile() {
    try {
      const response = await this.api.get('/profile');
      return response.data;
    } catch (error) {
      throw this.handleError(error);
    }
  }
  
  async analyze() {
    try {
      const response = await this.api.post('/analyze');
      return response.data;
    } catch (error) {
      throw this.handleError(error);
    }
  }
  
  async getLeaks() {
    try {
      const response = await this.api.get('/leaks');
      return response.data;
    } catch (error) {
      throw this.handleError(error);
    }
  }
  
  async optimize() {
    try {
      const response = await this.api.post('/optimize');
      return response.data;
    } catch (error) {
      throw this.handleError(error);
    }
  }
  
  async getTrends() {
    try {
      const response = await this.api.get('/trends');
      return response.data;
    } catch (error) {
      throw this.handleError(error);
    }
  }
  
  handleError(error) {
    if (error.response) {
      // Server responded with error status
      return new Error(
        `Server Error: ${error.response.status} - ${error.response.data.message || 'Unknown error'}`
      );
    } else if (error.request) {
      // Request was made but no response received
      return new Error('Network Error: Unable to reach server');
    } else {
      // Something else happened
      return new Error(`Error: ${error.message}`);
    }
  }
}

export const memoryService = new MemoryService();