import React, { useState, useEffect } from 'react';

const SplitPane = ({ children }) => {
  const [editorContent, setEditorContent] = useState('');

  return (
    <div className="split-pane">
      {React.Children.map(children, (child, index) => {
        if (index === 0) {
          // Editor component
          return React.cloneElement(child, {
            onContentChange: setEditorContent
          });
        } else if (index === 1) {
          // Preview component
          return React.cloneElement(child, {
            markdownContent: editorContent
          });
        }
        return child;
      })}
    </div>
  );
};

export default SplitPane;