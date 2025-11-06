import markdownService from './markdownService.js';

class ExportService {
  async exportToHtml(markdown) {
    try {
      const response = await markdownService.exportDocument(markdown, 'html');
      return response.data.content;
    } catch (error) {
      throw new Error(`Failed to export to HTML: ${error.message}`);
    }
  }

  async exportToPdf(markdown) {
    try {
      const response = await markdownService.exportDocument(markdown, 'pdf');
      return response.data.content;
    } catch (error) {
      throw new Error(`Failed to export to PDF: ${error.message}`);
    }
  }

  downloadContent(content, filename, mimeType) {
    const blob = new Blob([content], { type: mimeType });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
  }
}

export default new ExportService();