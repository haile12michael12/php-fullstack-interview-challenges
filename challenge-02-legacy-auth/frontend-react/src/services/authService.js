import axios from 'axios';

const API_BASE_URL = 'http://localhost:8000/api';

class AuthService {
  async register(userData) {
    try {
      const response = await axios.post(`${API_BASE_URL}/auth/register`, userData);
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.error || 'Registration failed');
    }
  }

  async login(credentials) {
    try {
      const response = await axios.post(`${API_BASE_URL}/auth/login`, credentials);
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.error || 'Login failed');
    }
  }

  async logout() {
    try {
      const response = await axios.post(`${API_BASE_URL}/auth/logout`);
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.error || 'Logout failed');
    }
  }

  async requestPasswordReset(email) {
    try {
      const response = await axios.post(`${API_BASE_URL}/auth/password/reset/request`, { email });
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.error || 'Password reset request failed');
    }
  }

  async resetPassword(token, newPassword) {
    try {
      const response = await axios.post(`${API_BASE_URL}/auth/password/reset`, { token, password: newPassword });
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.error || 'Password reset failed');
    }
  }
}

export default new AuthService();