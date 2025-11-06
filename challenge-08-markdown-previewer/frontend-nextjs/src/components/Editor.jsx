import React, { useState, useContext } from 'react';
import { ThemeContext } from '../context/ThemeContext.jsx';
import useDebounce from '../hooks/useDebounce.js';

const Editor = ({ onContentChange }) => {
  const [content, setContent] = useState('# Hello World\n\nThis is a **markdown** previewer.');
  const { theme } = useContext(ThemeContext);
  const debouncedContent = useDebounce(content, 300);

  React.useEffect(() => {
    if (onContentChange) {
      onContentChange(debouncedContent);
    }
  }, [debouncedContent, onContentChange]);

  return (
    <div className={`editor-container ${theme}`}>
      <textarea
        className="editor"
        value={content}
        onChange={(e) => setContent(e.target.value)}
        placeholder="Enter your markdown here..."
      />
    </div>
  );
};

export default Editor;