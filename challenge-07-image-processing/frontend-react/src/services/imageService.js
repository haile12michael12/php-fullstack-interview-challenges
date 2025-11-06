const API_BASE_URL = '/api';

class ImageService {
  async uploadImage(file) {
    const formData = new FormData();
    formData.append('image', file);

    const response = await fetch(`${API_BASE_URL}/upload`, {
      method: 'POST',
      body: formData
    });

    if (!response.ok) {
      throw new Error('Failed to upload image');
    }

    return await response.json();
  }

  async processImage(filename, operations) {
    const response = await fetch(`${API_BASE_URL}/process/${filename}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ operations })
    });

    if (!response.ok) {
      throw new Error('Failed to process image');
    }

    return await response.json();
  }

  async processBatch(images, operations) {
    const response = await fetch(`${API_BASE_URL}/batch`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ images, operations })
    });

    if (!response.ok) {
      throw new Error('Failed to process batch');
    }

    return await response.json();
  }

  async getBatchStatus(batchId) {
    const response = await fetch(`${API_BASE_URL}/batch/${batchId}`);

    if (!response.ok) {
      throw new Error('Failed to get batch status');
    }

    return await response.json();
  }
}

export default new ImageService();