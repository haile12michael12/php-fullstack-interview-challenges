import React, { useState } from 'react';

const ExportModal = ({ onClose }) => {
  const [exportFormat, setExportFormat] = useState('html');

  const handleExport = () => {
    // Logic to export the document
    alert(`Exporting as ${exportFormat}`);
    onClose();
  };

  return (
    <div className="modal-overlay">
      <div className="modal">
        <div className="modal-header">
          <h2>Export Document</h2>
          <button className="close-button" onClick={onClose}>×</button>
        </div>
        <div className="modal-body">
          <label>
            Format:
            <select 
              value={exportFormat} 
              onChange={(e) => setExportFormat(e.target.value)}
            >
              <option value="html">HTML</option>
              <option value="pdf">PDF</option>
            </select>
          </label>
        </div>
        <div className="modal-footer">
          <button onClick={onClose}>Cancel</button>
          <button onClick={handleExport}>Export</button>
        </div>
      </div>
    </div>
  );
};

export default ExportModal;