import React, { useState } from 'react';

const BatchUploader = ({ onBatchProcess }) => {
  const [files, setFiles] = useState([]);
  const [isProcessing, setIsProcessing] = useState(false);

  const handleFileChange = (event) => {
    const selectedFiles = Array.from(event.target.files);
    setFiles(selectedFiles);
  };

  const handleProcessBatch = async () => {
    if (files.length === 0) {
      alert('Please select files to process');
      return;
    }

    setIsProcessing(true);
    try {
      await onBatchProcess(files);
    } catch (error) {
      console.error('Batch processing error:', error);
    } finally {
      setIsProcessing(false);
    }
  };

  return (
    <div className="batch-uploader">
      <h2>Batch Image Processing</h2>
      <input 
        type="file" 
        multiple 
        accept="image/*" 
        onChange={handleFileChange} 
      />
      <div className="file-list">
        <h3>Selected Files ({files.length})</h3>
        <ul>
          {files.map((file, index) => (
            <li key={index}>{file.name}</li>
          ))}
        </ul>
      </div>
      <button 
        onClick={handleProcessBatch} 
        disabled={isProcessing || files.length === 0}
      >
        {isProcessing ? 'Processing...' : 'Process Batch'}
      </button>
    </div>
  );
};

export default BatchUploader;