import axios from 'axios';

const API_BASE_URL = 'http://localhost:8000/api';

class LongPollingService {
  async poll(clientId, categories = []) {
    try {
      const params = new URLSearchParams({
        client_id: clientId,
        categories: categories.join(',')
      });
      
      const response = await axios.get(`${API_BASE_URL}/poll?${params}`);
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.error || 'Polling failed');
    }
  }

  async sendNotification(notificationData) {
    try {
      const response = await axios.post(`${API_BASE_URL}/notify`, notificationData);
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.error || 'Failed to send notification');
    }
  }

  async getServerStatus() {
    try {
      const response = await axios.get(`${API_BASE_URL}/status`);
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.error || 'Failed to get server status');
    }
  }

  async subscribe(clientId, categories) {
    try {
      const response = await axios.post(`${API_BASE_URL}/subscribe`, {
        client_id: clientId,
        categories: categories
      });
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.error || 'Failed to subscribe');
    }
  }
}

export default new LongPollingService();