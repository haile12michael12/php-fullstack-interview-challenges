import React from 'react';
import { useMemory } from '../context/MemoryContext';
import PerformanceChart from '../components/PerformanceChart';
import MemoryProfiler from '../components/MemoryProfiler';
import MemoryLeakDetector from '../components/MemoryLeakDetector';
import MemoryOptimizer from '../components/MemoryOptimizer';

function Dashboard() {
  const { memoryData, loading, error } = useMemory();
  
  if (loading && !memoryData.currentUsage) {
    return <div className="loading">Loading memory data...</div>;
  }
  
  if (error) {
    return <div className="error">Error: {error}</div>;
  }
  
  return (
    <div className="dashboard">
      <div className="stats-grid">
        <div className="stat-card">
          <div className="stat-label">Current Memory Usage</div>
          <div className="stat-value">{memoryData.formatted_usage}</div>
        </div>
        
        <div className="stat-card">
          <div className="stat-label">Peak Memory Usage</div>
          <div className="stat-value">{memoryData.formatted_peak}</div>
        </div>
        
        <div className="stat-card">
          <div className="stat-label">Memory Efficiency</div>
          <div className="stat-value">92%</div>
        </div>
        
        <div className="stat-card">
          <div className="stat-label">Active Alerts</div>
          <div className="stat-value">2</div>
        </div>
      </div>
      
      <div className="card">
        <div className="card-header">
          <h2>Memory Usage Trend</h2>
        </div>
        <div className="chart-container">
          <PerformanceChart />
        </div>
      </div>
      
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '1.5rem' }}>
        <MemoryProfiler />
        <MemoryLeakDetector />
      </div>
      
      <MemoryOptimizer />
    </div>
  );
}

export default Dashboard;