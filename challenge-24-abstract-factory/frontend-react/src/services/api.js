// API service for interacting with the backend

const API_BASE_URL = '/api';

class ApiService {
  constructor() {
    this.baseUrl = API_BASE_URL;
    this.token = localStorage.getItem('authToken');
  }

  // Set authentication token
  setToken(token) {
    this.token = token;
    localStorage.setItem('authToken', token);
  }

  // Clear authentication token
  clearToken() {
    this.token = null;
    localStorage.removeItem('authToken');
  }

  // Generic request method
  async request(endpoint, options = {}) {
    const url = `${this.baseUrl}${endpoint}`;
    const config = {
      headers: {
        'Content-Type': 'application/json',
        ...options.headers,
      },
      ...options,
    };

    // Add authentication header if token exists
    if (this.token) {
      config.headers.Authorization = `Bearer ${this.token}`;
    }

    try {
      const response = await fetch(url, config);
      const data = await response.json();
      
      if (response.status === 401) {
        this.clearToken();
      }
      
      return { data, response };
    } catch (error) {
      throw new Error(`API request failed: ${error.message}`);
    }
  }

  // Authentication endpoints
  async login(credentials) {
    const { data } = await this.request('/auth/login', {
      method: 'POST',
      body: JSON.stringify(credentials),
    });
    
    if (data.status === 'success' && data.token) {
      this.setToken(data.token);
    }
    
    return data;
  }

  async register(userData) {
    const { data } = await this.request('/auth/register', {
      method: 'POST',
      body: JSON.stringify(userData),
    });
    
    return data;
  }

  async logout() {
    const { data } = await this.request('/auth/logout', {
      method: 'POST',
    });
    
    this.clearToken();
    return data;
  }

  // Factory endpoints
  async createMySqlConnection() {
    return this.request('/factory/mysql');
  }

  async createPostgreSqlConnection() {
    return this.request('/factory/postgresql');
  }

  async createSqliteConnection() {
    return this.request('/factory/sqlite');
  }

  async testConnectionPool() {
    return this.request('/factory/pool');
  }
}

// Create and export a singleton instance
const apiService = new ApiService();
export default apiService;