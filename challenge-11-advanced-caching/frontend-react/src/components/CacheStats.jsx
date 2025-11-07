import React from 'react';
import { useCacheStats } from '../hooks/useCacheStats';
import { formatBytes } from '../utils/formatStats';

const CacheStats = () => {
  const { stats, loading, error } = useCacheStats();

  if (loading) {
    return <div>Loading cache statistics...</div>;
  }

  if (error) {
    return <div className="error">Error loading statistics: {error.message}</div>;
  }

  if (!stats) {
    return <div>No statistics available</div>;
  }

  return (
    <div className="cache-stats">
      <div className="stats-grid">
        <div className="stat-card">
          <h4>Hit Rate</h4>
          <div className="stat-value">{(stats.hit_rate * 100).toFixed(2)}%</div>
        </div>
        
        <div className="stat-card">
          <h4>Memory Usage</h4>
          <div className="stat-value">
            {stats.memory_usage?.redis?.used_memory_human || 'N/A'}
          </div>
        </div>
        
        <div className="stat-card">
          <h4>Last Updated</h4>
          <div className="stat-value">
            {new Date(stats.timestamp * 1000).toLocaleTimeString()}
          </div>
        </div>
      </div>
      
      {stats.memory_usage && (
        <div className="memory-details">
          <h4>Memory Details</h4>
          <ul>
            {Object.entries(stats.memory_usage).map(([key, value]) => (
              <li key={key}>
                <strong>{key}:</strong> {formatBytes(value.used_memory || 0)}
              </li>
            ))}
          </ul>
        </div>
      )}
    </div>
  );
};

export default CacheStats;