import React, { useState, useEffect } from 'react';
import ErrorList from './ErrorList.jsx';
import LogViewer from './LogViewer.jsx';
import AnalyticsPanel from './AnalyticsPanel.jsx';
import FilterBar from '../ui/FilterBar.jsx';

const LogDashboard = () => {
  const [logs, setLogs] = useState([]);
  const [selectedLog, setSelectedLog] = useState(null);
  const [filters, setFilters] = useState({});
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchLogs();
  }, [filters]);

  const fetchLogs = async () => {
    setLoading(true);
    try {
      const response = await fetch('/api/logs');
      const data = await response.json();
      setLogs(data.logs || []);
    } catch (error) {
      console.error('Failed to fetch logs:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleFilterChange = (newFilters) => {
    setFilters(newFilters);
  };

  const handleLogSelect = (log) => {
    setSelectedLog(log);
  };

  const handleRefresh = () => {
    fetchLogs();
  };

  return (
    <div className="log-dashboard">
      <div className="dashboard-header">
        <h1>Error Logger Dashboard</h1>
        <button onClick={handleRefresh}>Refresh</button>
      </div>
      
      <FilterBar onFilterChange={handleFilterChange} />
      
      <div className="dashboard-content">
        <div className="left-panel">
          <ErrorList 
            logs={logs} 
            onSelectLog={handleLogSelect} 
            loading={loading}
          />
        </div>
        
        <div className="right-panel">
          {selectedLog ? (
            <LogViewer log={selectedLog} />
          ) : (
            <AnalyticsPanel />
          )}
        </div>
      </div>
    </div>
  );
};

export default LogDashboard;