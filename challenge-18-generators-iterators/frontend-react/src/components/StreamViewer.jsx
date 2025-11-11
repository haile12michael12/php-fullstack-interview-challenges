import React, { useState, useEffect } from 'react';
import { streamData } from '../services/generatorService';

const StreamViewer = () => {
  const [streamData, setStreamData] = useState([]);
  const [isStreaming, setIsStreaming] = useState(false);
  const [error, setError] = useState(null);

  const startStreaming = async () => {
    setIsStreaming(true);
    setError(null);
    
    try {
      // Simulate streaming data
      for (let i = 0; i < 10; i++) {
        const newData = await streamData();
        setStreamData(prev => [...prev, ...newData]);
        await new Promise(resolve => setTimeout(resolve, 1000)); // 1 second delay
      }
    } catch (err) {
      setError(err.message);
    } finally {
      setIsStreaming(false);
    }
  };

  const clearStream = () => {
    setStreamData([]);
  };

  return (
    <div className="stream-viewer">
      <h2>Stream Viewer</h2>
      
      <div className="controls">
        <button onClick={startStreaming} disabled={isStreaming}>
          {isStreaming ? 'Streaming...' : 'Start Streaming'}
        </button>
        <button onClick={clearStream}>Clear</button>
      </div>
      
      {error && <div className="error">Error: {error}</div>}
      
      <div className="stream-display">
        <h3>Stream Data</h3>
        <div className="stream-content">
          {streamData.map((item, index) => (
            <div key={index} className="stream-item">
              {item}
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default StreamViewer;