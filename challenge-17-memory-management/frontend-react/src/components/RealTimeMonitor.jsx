import React from 'react';
import { useMemory } from '../context/MemoryContext';

function RealTimeMonitor() {
  const { memoryData, loading } = useMemory();
  
  return (
    <div className="card">
      <div className="card-header">
        <h2>Real-time Monitor</h2>
      </div>
      
      <div className="monitor-stats">
        <div className="stat-item">
          <span className="label">Current Usage:</span>
          <span className="value">{memoryData.formatted_usage}</span>
        </div>
        
        <div className="stat-item">
          <span className="label">Peak Usage:</span>
          <span className="value">{memoryData.formatted_peak}</span>
        </div>
        
        <div className="stat-item">
          <span className="label">Status:</span>
          <span className="value" style={{ color: '#10b981' }}>Operational</span>
        </div>
        
        <div className="stat-item">
          <span className="label">Last Updated:</span>
          <span className="value">{new Date().toLocaleTimeString()}</span>
        </div>
      </div>
      
      <div className="memory-bar">
        <div className="memory-bar-fill" style={{ width: '45%' }}></div>
      </div>
      
      <div className="monitor-info">
        <p>Monitoring memory usage in real-time. Alerts will appear in the panel below.</p>
      </div>
    </div>
  );
}

export default RealTimeMonitor;