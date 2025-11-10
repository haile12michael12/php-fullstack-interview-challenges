import React from 'react';
import { useMemory } from '../context/MemoryContext';

function AlertsPanel() {
  const { alerts } = useMemory();
  
  // Sample alerts for demonstration
  const sampleAlerts = [
    {
      id: 1,
      type: 'memory_leak',
      severity: 'high',
      message: 'Potential memory leak detected in data processing module',
      timestamp: '2023-05-15 14:30:22'
    },
    {
      id: 2,
      type: 'spike_usage',
      severity: 'medium',
      message: 'Memory usage spike detected, currently at 75% capacity',
      timestamp: '2023-05-15 14:28:15'
    }
  ];
  
  const allAlerts = alerts.length > 0 ? alerts : sampleAlerts;
  
  return (
    <div className="card">
      <div className="card-header">
        <h2>Memory Alerts</h2>
      </div>
      
      <div className="alerts-list">
        {allAlerts.map((alert) => (
          <div key={alert.id} className={`alert alert-${alert.severity || 'low'}`}>
            <div className="alert-header">
              <span className="alert-type">{alert.type || 'Memory Alert'}</span>
              <span className="alert-time">{alert.timestamp}</span>
            </div>
            <div className="alert-message">
              {alert.message || 'Memory usage alert'}
            </div>
          </div>
        ))}
        
        {allAlerts.length === 0 && (
          <div className="no-alerts">
            <p>No active alerts. System memory usage is within normal parameters.</p>
          </div>
        )}
      </div>
    </div>
  );
}

export default AlertsPanel;