import React from 'react';
import CacheStats from './CacheStats';
import PerformanceChart from './PerformanceChart';
import CacheControls from './CacheControls';

const CacheDashboard = () => {
  return (
    <div className="cache-dashboard">
      <h1>Cache Monitoring Dashboard</h1>
      
      <div className="dashboard-grid">
        <div className="dashboard-section">
          <h2>Cache Statistics</h2>
          <CacheStats />
        </div>
        
        <div className="dashboard-section">
          <h2>Performance Metrics</h2>
          <PerformanceChart />
        </div>
        
        <div className="dashboard-section">
          <h2>Cache Controls</h2>
          <CacheControls />
        </div>
      </div>
    </div>
  );
};

export default CacheDashboard;