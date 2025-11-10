import React, { useState } from 'react';
import { useMemory } from '../context/MemoryContext';

function MemoryProfiler() {
  const { memoryData, fetchMemoryData } = useMemory();
  const [snapshots, setSnapshots] = useState([]);
  
  const takeSnapshot = () => {
    fetchMemoryData();
    const newSnapshot = {
      id: Date.now(),
      timestamp: new Date().toLocaleTimeString(),
      usage: memoryData.formatted_usage,
      peak: memoryData.formatted_peak,
    };
    setSnapshots([...snapshots, newSnapshot]);
  };
  
  return (
    <div className="card">
      <div className="card-header">
        <h2>Memory Profiler</h2>
        <button className="btn btn-primary" onClick={takeSnapshot}>
          Take Snapshot
        </button>
      </div>
      
      <div className="profiler-stats">
        <div className="stat-grid">
          <div className="stat-item">
            <span className="label">Current Usage:</span>
            <span className="value">{memoryData.formatted_usage}</span>
          </div>
          <div className="stat-item">
            <span className="label">Peak Usage:</span>
            <span className="value">{memoryData.formatted_peak}</span>
          </div>
        </div>
      </div>
      
      <div className="snapshots">
        <h3>Snapshots</h3>
        {snapshots.length === 0 ? (
          <p>No snapshots taken yet.</p>
        ) : (
          <table>
            <thead>
              <tr>
                <th>Time</th>
                <th>Usage</th>
                <th>Peak</th>
              </tr>
            </thead>
            <tbody>
              {snapshots.map((snapshot) => (
                <tr key={snapshot.id}>
                  <td>{snapshot.timestamp}</td>
                  <td>{snapshot.usage}</td>
                  <td>{snapshot.peak}</td>
                </tr>
              ))}
            </tbody>
          </table>
        )}
      </div>
    </div>
  );
}

export default MemoryProfiler;