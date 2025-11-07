import React, { useEffect, useState } from 'react';
import { useCacheStats } from '../hooks/useCacheStats';

const PerformanceChart = () => {
  const { stats, loading } = useCacheStats();
  const [chartData, setChartData] = useState([]);

  useEffect(() => {
    if (stats) {
      // Transform stats data for charting
      const data = [
        { name: 'Hit Rate', value: stats.hit_rate * 100 },
        { name: 'Miss Rate', value: (1 - stats.hit_rate) * 100 }
      ];
      setChartData(data);
    }
  }, [stats]);

  if (loading) {
    return <div>Loading performance data...</div>;
  }

  return (
    <div className="performance-chart">
      <h3>Cache Performance</h3>
      <div className="chart-container">
        {chartData.map((item, index) => (
          <div key={index} className="chart-bar">
            <div 
              className="bar-fill"
              style={{ height: `${item.value}%` }}
            >
              <span className="bar-value">{item.value.toFixed(1)}%</span>
            </div>
            <div className="bar-label">{item.name}</div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default PerformanceChart;