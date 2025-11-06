const API_BASE_URL = '/api';

class MarkdownService {
  async generatePreview(markdown) {
    const response = await fetch(`${API_BASE_URL}/preview`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ markdown }),
    });

    if (!response.ok) {
      throw new Error('Failed to generate preview');
    }

    return await response.json();
  }

  async exportDocument(markdown, format) {
    const response = await fetch(`${API_BASE_URL}/export`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ markdown, format }),
    });

    if (!response.ok) {
      throw new Error('Failed to export document');
    }

    return await response.json();
  }
}

export default new MarkdownService();