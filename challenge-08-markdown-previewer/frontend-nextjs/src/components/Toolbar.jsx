import React, { useContext, useState } from 'react';
import { ThemeContext } from '../context/ThemeContext.jsx';
import ThemeToggle from './ThemeToggle.jsx';
import ExportModal from './ExportModal.jsx';

const Toolbar = () => {
  const { theme } = useContext(ThemeContext);
  const [showExportModal, setShowExportModal] = useState(false);

  const handleExport = () => {
    setShowExportModal(true);
  };

  const handleNewDocument = () => {
    // Logic to create a new document
    window.location.reload();
  };

  const handleSaveDocument = () => {
    // Logic to save the document
    alert('Document saved!');
  };

  return (
    <div className={`toolbar ${theme}`}>
      <div className="toolbar-left">
        <button onClick={handleNewDocument}>New</button>
        <button onClick={handleSaveDocument}>Save</button>
      </div>
      <div className="toolbar-right">
        <button onClick={handleExport}>Export</button>
        <ThemeToggle />
      </div>
      
      {showExportModal && (
        <ExportModal onClose={() => setShowExportModal(false)} />
      )}
    </div>
  );
};

export default Toolbar;