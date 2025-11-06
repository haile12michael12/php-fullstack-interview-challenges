import { useState, useEffect } from 'react';

const useMarkdown = (markdown) => {
  const [html, setHtml] = useState('');

  useEffect(() => {
    // In a real application, you would call the backend API
    // For now, we'll do a simple client-side conversion
    
    let convertedHtml = markdown
      .replace(/^# (.*)$/gm, '<h1>$1</h1>')
      .replace(/^## (.*)$/gm, '<h2>$1</h2>')
      .replace(/^### (.*)$/gm, '<h3>$1</h3>')
      .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
      .replace(/\*(.*?)\*/g, '<em>$1</em>')
      .replace(/^\* (.*)$/gm, '<li>$1</li>')
      .replace(/(<li>.*<\/li>)/gs, '<ul>$1</ul>')
      .replace(/^(?!<[\/]?h[1-6]>)(?!<ul>)(?!<\/ul>)(?!<li>)(?!<\/li>)(.*)$/gm, '<p>$1</p>');

    setHtml(convertedHtml);
  }, [markdown]);

  return html;
};

export default useMarkdown;