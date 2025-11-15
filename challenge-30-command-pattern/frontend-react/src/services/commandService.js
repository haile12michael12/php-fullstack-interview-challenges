import axios from 'axios';

const API_BASE_URL = '/api';

class CommandService {
  /**
   * Queue a new command
   * @param {Object} commandData - Command data to queue
   * @returns {Promise<Object>} Response data
   */
  async queueCommand(commandData) {
    try {
      const response = await axios.post(`${API_BASE_URL}/commands/queue`, commandData);
      return response.data;
    } catch (error) {
      console.error('Error queuing command:', error);
      throw error;
    }
  }

  /**
   * Get command history
   * @returns {Promise<Array>} Command history
   */
  async getCommandHistory() {
    try {
      const response = await axios.get(`${API_BASE_URL}/commands/history`);
      return response.data.history || [];
    } catch (error) {
      console.error('Error fetching command history:', error);
      throw error;
    }
  }

  /**
   * Undo last command
   * @returns {Promise<Object>} Response data
   */
  async undoLastCommand() {
    try {
      const response = await axios.post(`${API_BASE_URL}/commands/undo`);
      return response.data;
    } catch (error) {
      console.error('Error undoing command:', error);
      throw error;
    }
  }

  /**
   * Redo last undone command
   * @returns {Promise<Object>} Response data
   */
  async redoLastCommand() {
    try {
      const response = await axios.post(`${API_BASE_URL}/commands/redo`);
      return response.data;
    } catch (error) {
      console.error('Error redoing command:', error);
      throw error;
    }
  }

  /**
   * Get queue status
   * @returns {Promise<Object>} Queue status
   */
  async getQueueStatus() {
    try {
      const response = await axios.get(`${API_BASE_URL}/queue/status`);
      return response.data;
    } catch (error) {
      console.error('Error fetching queue status:', error);
      throw error;
    }
  }

  /**
   * Clear command history
   * @returns {Promise<Object>} Response data
   */
  async clearHistory() {
    try {
      const response = await axios.delete(`${API_BASE_URL}/commands/clear`);
      return response.data;
    } catch (error) {
      console.error('Error clearing history:', error);
      throw error;
    }
  }
}

export default new CommandService();