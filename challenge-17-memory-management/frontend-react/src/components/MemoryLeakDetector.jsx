import React, { useState } from 'react';
import { useMemory } from '../context/MemoryContext';

function MemoryLeakDetector() {
  const { alerts } = useMemory();
  const [isScanning, setIsScanning] = useState(false);
  const [scanResults, setScanResults] = useState(null);
  
  const scanForLeaks = async () => {
    setIsScanning(true);
    // Simulate scanning process
    setTimeout(() => {
      setScanResults({
        potentialLeaks: 2,
        circularReferences: 1,
        unreferencedObjects: 5,
        suggestions: [
          'Check for circular references between User and Order objects',
          'Ensure proper resource cleanup in database connections',
          'Use weak references for parent-child relationships'
        ]
      });
      setIsScanning(false);
    }, 2000);
  };
  
  return (
    <div className="card">
      <div className="card-header">
        <h2>Memory Leak Detector</h2>
        <button 
          className={`btn ${isScanning ? 'btn-secondary' : 'btn-primary'}`}
          onClick={scanForLeaks}
          disabled={isScanning}
        >
          {isScanning ? 'Scanning...' : 'Scan for Leaks'}
        </button>
      </div>
      
      {isScanning && (
        <div className="scan-progress">
          <p>Scanning for memory leaks... This may take a moment.</p>
        </div>
      )}
      
      {scanResults && (
        <div className="scan-results">
          <div className="results-summary">
            <div className="stat-item">
              <span className="label">Potential Leaks:</span>
              <span className="value">{scanResults.potentialLeaks}</span>
            </div>
            <div className="stat-item">
              <span className="label">Circular References:</span>
              <span className="value">{scanResults.circularReferences}</span>
            </div>
            <div className="stat-item">
              <span className="label">Unreferenced Objects:</span>
              <span className="value">{scanResults.unreferencedObjects}</span>
            </div>
          </div>
          
          <div className="suggestions">
            <h3>Recommendations</h3>
            <ul>
              {scanResults.suggestions.map((suggestion, index) => (
                <li key={index}>{suggestion}</li>
              ))}
            </ul>
          </div>
        </div>
      )}
      
      {!scanResults && !isScanning && (
        <div className="no-scan">
          <p>Click "Scan for Leaks" to detect potential memory leaks in your application.</p>
        </div>
      )}
    </div>
  );
}

export default MemoryLeakDetector;