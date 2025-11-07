import axios from 'axios';

const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://localhost:8080/api';

class CacheService {
  constructor() {
    this.api = axios.create({
      baseURL: API_BASE_URL,
      timeout: 5000,
    });
  }

  async getStats() {
    try {
      const response = await this.api.get('/cache/stats');
      return response.data;
    } catch (error) {
      throw new Error(`Failed to fetch cache stats: ${error.message}`);
    }
  }

  async warmCache() {
    try {
      const response = await this.api.post('/cache/warm');
      return response.data;
    } catch (error) {
      throw new Error(`Failed to warm cache: ${error.message}`);
    }
  }

  async flushCache() {
    try {
      const response = await this.api.delete('/cache/flush');
      return response.data;
    } catch (error) {
      throw new Error(`Failed to flush cache: ${error.message}`);
    }
  }

  async invalidateCache(keys) {
    try {
      const response = await this.api.post('/cache/invalidate', { keys });
      return response.data;
    } catch (error) {
      throw new Error(`Failed to invalidate cache: ${error.message}`);
    }
  }
}

export const cacheService = new CacheService();