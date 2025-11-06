import React, { useState, useEffect, useContext } from 'react';
import { ThemeContext } from '../context/ThemeContext.jsx';
import useMarkdown from '../hooks/useMarkdown.js';

const Preview = ({ markdownContent }) => {
  const { theme } = useContext(ThemeContext);
  const [content, setContent] = useState(markdownContent || '# Hello World\n\nThis is a **markdown** previewer.');
  const renderedHtml = useMarkdown(content);

  useEffect(() => {
    if (markdownContent !== undefined) {
      setContent(markdownContent);
    }
  }, [markdownContent]);

  return (
    <div className={`preview-container ${theme}`}>
      <div 
        className="preview-content"
        dangerouslySetInnerHTML={{ __html: renderedHtml }}
      />
    </div>
  );
};

export default Preview;