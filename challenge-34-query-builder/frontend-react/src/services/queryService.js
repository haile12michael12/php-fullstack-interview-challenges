const API_BASE_URL = '/api/query';

class QueryService {
  /**
   * Build and execute a query
   * @param {Object} queryData - The query data to execute
   * @returns {Promise<Object>} The response from the server
   */
  static async executeQuery(queryData) {
    try {
      const response = await fetch(`${API_BASE_URL}/build`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(queryData),
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Failed to execute query');
      }

      return await response.json();
    } catch (error) {
      throw new Error(error.message || 'Network error occurred');
    }
  }

  /**
   * Build a SELECT query
   * @param {Object} queryData - The query data
   * @returns {Promise<Object>} The response from the server
   */
  static async buildSelectQuery(queryData) {
    return this.executeQuery({ ...queryData, type: 'select' });
  }

  /**
   * Build an INSERT query
   * @param {Object} queryData - The query data
   * @returns {Promise<Object>} The response from the server
   */
  static async buildInsertQuery(queryData) {
    return this.executeQuery({ ...queryData, type: 'insert' });
  }

  /**
   * Build an UPDATE query
   * @param {Object} queryData - The query data
   * @returns {Promise<Object>} The response from the server
   */
  static async buildUpdateQuery(queryData) {
    return this.executeQuery({ ...queryData, type: 'update' });
  }

  /**
   * Build a DELETE query
   * @param {Object} queryData - The query data
   * @returns {Promise<Object>} The response from the server
   */
  static async buildDeleteQuery(queryData) {
    return this.executeQuery({ ...queryData, type: 'delete' });
  }

  /**
   * Get query history
   * @returns {Promise<Object>} The query history
   */
  static async getQueryHistory() {
    try {
      const response = await fetch(`${API_BASE_URL}/history`);
      
      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Failed to fetch query history');
      }

      return await response.json();
    } catch (error) {
      throw new Error(error.message || 'Network error occurred');
    }
  }
}

export default QueryService;