const API_BASE_URL = '/api';

class LogService {
  async getAllLogs(filters = {}) {
    try {
      const queryParams = new URLSearchParams(filters).toString();
      const url = `${API_BASE_URL}/logs${queryParams ? `?${queryParams}` : ''}`;
      
      const response = await fetch(url);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      return data.logs || [];
    } catch (error) {
      console.error('Error fetching logs:', error);
      throw error;
    }
  }

  async getLogById(id) {
    try {
      const response = await fetch(`${API_BASE_URL}/logs/${id}`);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      return data.log;
    } catch (error) {
      console.error(`Error fetching log ${id}:`, error);
      throw error;
    }
  }

  async deleteLog(id) {
    try {
      const response = await fetch(`${API_BASE_URL}/logs/${id}`, {
        method: 'DELETE'
      });
      
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      return data;
    } catch (error) {
      console.error(`Error deleting log ${id}:`, error);
      throw error;
    }
  }

  async getAnalytics() {
    try {
      const response = await fetch(`${API_BASE_URL}/analytics`);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      
      const data = await response.json();
      return data.analytics;
    } catch (error) {
      console.error('Error fetching analytics:', error);
      throw error;
    }
  }
}

export default new LogService();