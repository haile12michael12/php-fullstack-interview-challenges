import markdownService from '../services/markdownService.js';

// Mock fetch
global.fetch = jest.fn();

describe('MarkdownService', () => {
  beforeEach(() => {
    fetch.mockClear();
  });

  test('generatePreview calls the correct endpoint', async () => {
    fetch.mockResolvedValueOnce({
      ok: true,
      json: () => Promise.resolve({ data: { renderedHtml: '<h1>Hello</h1>' } }),
    });

    const markdown = '# Hello';
    const result = await markdownService.generatePreview(markdown);

    expect(fetch).toHaveBeenCalledWith('/api/preview', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ markdown }),
    });
    expect(result).toEqual({ data: { renderedHtml: '<h1>Hello</h1>' } });
  });

  test('generatePreview throws error on failed request', async () => {
    fetch.mockResolvedValueOnce({
      ok: false,
    });

    const markdown = '# Hello';
    
    await expect(markdownService.generatePreview(markdown)).rejects.toThrow('Failed to generate preview');
  });
});