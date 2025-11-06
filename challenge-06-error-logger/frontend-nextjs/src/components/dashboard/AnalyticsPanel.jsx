import React, { useState, useEffect } from 'react';
import Card from '../ui/Card.jsx';

const AnalyticsPanel = () => {
  const [analytics, setAnalytics] = useState({});
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchAnalytics();
  }, []);

  const fetchAnalytics = async () => {
    setLoading(true);
    try {
      const response = await fetch('/api/analytics');
      const data = await response.json();
      setAnalytics(data.analytics || {});
    } catch (error) {
      console.error('Failed to fetch analytics:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return <div className="analytics-panel">Loading analytics...</div>;
  }

  return (
    <div className="analytics-panel">
      <h2>Analytics Overview</h2>
      
      <div className="analytics-cards">
        <Card className="analytics-card">
          <h3>Total Errors</h3>
          <div className="metric">{analytics.totalErrors || 0}</div>
        </Card>
        
        <Card className="analytics-card">
          <h3>Average per Day</h3>
          <div className="metric">{analytics.averageErrorsPerDay || 0}</div>
        </Card>
      </div>
      
      {analytics.errorCountsByLevel && (
        <Card className="analytics-card">
          <h3>Errors by Level</h3>
          <div className="chart">
            {Object.entries(analytics.errorCountsByLevel).map(([level, count]) => (
              <div key={level} className="chart-bar">
                <span className="level">{level}:</span>
                <span className="count">{count}</span>
              </div>
            ))}
          </div>
        </Card>
      )}
    </div>
  );
};

export default AnalyticsPanel;