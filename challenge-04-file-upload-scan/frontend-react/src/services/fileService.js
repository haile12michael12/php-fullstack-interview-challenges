import axios from 'axios';

const API_BASE_URL = 'http://localhost:8000/api';

class FileService {
  async uploadFile(file) {
    const formData = new FormData();
    formData.append('file', file);
    
    try {
      const response = await axios.post(`${API_BASE_URL}/upload`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      });
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.error || 'File upload failed');
    }
  }

  async listFiles() {
    try {
      const response = await axios.get(`${API_BASE_URL}/files`);
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.error || 'Failed to list files');
    }
  }

  async getFile(fileId) {
    try {
      const response = await axios.get(`${API_BASE_URL}/files/${fileId}`);
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.error || 'Failed to get file');
    }
  }
}

export default new FileService();