import React, { useState, useEffect } from 'react';
import { memoryService } from '../services/memoryService';
import PerformanceChart from '../components/PerformanceChart';

function Trends() {
  const [trendData, setTrendData] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  
  useEffect(() => {
    fetchTrendData();
  }, []);
  
  const fetchTrendData = async () => {
    try {
      setLoading(true);
      const response = await memoryService.getTrends();
      setTrendData(response.data.history || []);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };
  
  if (loading) {
    return <div className="loading">Loading trend data...</div>;
  }
  
  if (error) {
    return <div className="error">Error: {error}</div>;
  }
  
  return (
    <div className="trends">
      <div className="card">
        <div className="card-header">
          <h2>Memory Usage Trends</h2>
          <button className="btn btn-primary" onClick={fetchTrendData}>
            Refresh Data
          </button>
        </div>
        <div className="chart-container">
          <PerformanceChart data={trendData} />
        </div>
      </div>
      
      <div className="card">
        <div className="card-header">
          <h2>Trend Analysis</h2>
        </div>
        <div className="trend-analysis">
          <div className="analysis-item">
            <h3>Average Memory Usage</h3>
            <p>24.5 MB</p>
          </div>
          <div className="analysis-item">
            <h3>Peak Memory Usage</h3>
            <p>38.2 MB</p>
          </div>
          <div className="analysis-item">
            <h3>Growth Rate</h3>
            <p>2.3% per hour</p>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Trends;