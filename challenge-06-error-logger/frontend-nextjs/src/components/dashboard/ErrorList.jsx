import React from 'react';
import Card from '../ui/Card.jsx';

const ErrorList = ({ logs, onSelectLog, loading }) => {
  if (loading) {
    return <div className="error-list">Loading...</div>;
  }

  if (!logs || logs.length === 0) {
    return <div className="error-list">No errors found</div>;
  }

  const getLevelClass = (level) => {
    switch (level.toLowerCase()) {
      case 'error':
      case 'critical':
      case 'alert':
      case 'emergency':
        return 'error';
      case 'warning':
        return 'warning';
      case 'notice':
        return 'notice';
      default:
        return 'info';
    }
  };

  return (
    <div className="error-list">
      <h2>Recent Errors</h2>
      <div className="error-items">
        {logs.map((log) => (
          <Card 
            key={log.id} 
            className={`error-item ${getLevelClass(log.level)}`}
            onClick={() => onSelectLog(log)}
          >
            <div className="error-header">
              <span className="error-level">{log.level.toUpperCase()}</span>
              <span className="error-timestamp">{log.timestamp}</span>
            </div>
            <div className="error-message">{log.message}</div>
            {log.context && (
              <div className="error-context">
                Context: {JSON.stringify(log.context)}
              </div>
            )}
          </Card>
        ))}
      </div>
    </div>
  );
};

export default ErrorList;