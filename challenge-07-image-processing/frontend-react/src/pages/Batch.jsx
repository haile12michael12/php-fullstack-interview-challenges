import React from 'react';
import BatchUploader from '../components/BatchUploader.jsx';

const Batch = () => {
  const handleBatchProcess = async (files) => {
    // Implementation for batch processing
    console.log('Processing batch of', files.length, 'files');
    
    // Simulate processing
    return new Promise((resolve) => {
      setTimeout(() => {
        resolve({ success: true, processed: files.length });
      }, 2000);
    });
  };

  return (
    <div className="batch-page">
      <header>
        <h1>Batch Processing</h1>
        <p>Process multiple images at once with the same settings</p>
      </header>
      
      <main>
        <BatchUploader onBatchProcess={handleBatchProcess} />
      </main>
    </div>
  );
};

export default Batch;