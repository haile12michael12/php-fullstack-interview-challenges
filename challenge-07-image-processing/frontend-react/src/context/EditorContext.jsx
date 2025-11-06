import React, { createContext, useContext, useState } from 'react';

const EditorContext = createContext();

export const EditorProvider = ({ children }) => {
  const [editorState, setEditorState] = useState({
    currentImage: null,
    filters: {},
    operations: [],
    history: []
  });

  const updateImage = (image) => {
    setEditorState(prev => ({
      ...prev,
      currentImage: image
    }));
  };

  const applyFilter = (filterType, options) => {
    const operation = { type: 'filter', filterType, options };
    setEditorState(prev => ({
      ...prev,
      filters: {
        ...prev.filters,
        [filterType]: options
      },
      operations: [...prev.operations, operation],
      history: [...prev.history, { ...prev }]
    }));
  };

  const resetEditor = () => {
    setEditorState({
      currentImage: null,
      filters: {},
      operations: [],
      history: []
    });
  };

  const undo = () => {
    if (editorState.history.length > 0) {
      const previousState = editorState.history[editorState.history.length - 1];
      setEditorState({
        ...previousState,
        history: editorState.history.slice(0, -1)
      });
    }
  };

  return (
    <EditorContext.Provider value={{
      ...editorState,
      updateImage,
      applyFilter,
      resetEditor,
      undo
    }}>
      {children}
    </EditorContext.Provider>
  );
};

export const useEditor = () => {
  const context = useContext(EditorContext);
  if (!context) {
    throw new Error('useEditor must be used within an EditorProvider');
  }
  return context;
};