// API service for interacting with the backend

const API_BASE_URL = '/api';

class ApiService {
  constructor() {
    this.baseUrl = API_BASE_URL;
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

    try {
      const response = await fetch(url, config);
      const data = await response.json();
      return { data, response };
    } catch (error) {
      throw new Error(`API request failed: ${error.message}`);
    }
  }

  // Magic Methods endpoints
  async getFluentInterface() {
    return this.request('/magic/fluent');
  }

  async getDynamicProxy() {
    return this.request('/magic/proxy');
  }

  async getMethodInterceptor() {
    return this.request('/magic/interceptor');
  }

  // Entity endpoints
  async getUsers() {
    return this.request('/entities/users');
  }

  async getUser(id) {
    return this.request(`/entities/users/${id}`);
  }

  async createUser(userData) {
    return this.request('/entities/users', {
      method: 'POST',
      body: JSON.stringify(userData),
    });
  }

  async updateUser(id, userData) {
    return this.request(`/entities/users/${id}`, {
      method: 'PUT',
      body: JSON.stringify(userData),
    });
  }

  async deleteUser(id) {
    return this.request(`/entities/users/${id}`, {
      method: 'DELETE',
    });
  }

  // Query endpoints
  async queryUsers(params = {}) {
    const queryString = new URLSearchParams(params).toString();
    const endpoint = `/query/users${queryString ? `?${queryString}` : ''}`;
    return this.request(endpoint);
  }

  async queryPosts(params = {}) {
    const queryString = new URLSearchParams(params).toString();
    const endpoint = `/query/posts${queryString ? `?${queryString}` : ''}`;
    return this.request(endpoint);
  }

  async executeCustomQuery(queryData) {
    return this.request('/query/custom', {
      method: 'POST',
      body: JSON.stringify(queryData),
    });
  }
}

// Create and export a singleton instance
const apiService = new ApiService();
export default apiService;