import React, { useState, useEffect } from 'react';
import MetricsChart from './MetricsChart';
import ConnectionStatus from './ConnectionStatus';
import ControlPanel from './ControlPanel';
import useWebSocket from '../hooks/useWebSocket';
import DashboardService from '../services/dashboardService';

const Dashboard = () => {
  const [metricsData, setMetricsData] = useState([]);
  const [formattedMetrics, setFormattedMetrics] = useState({});
  const [isSubscribed, setIsSubscribed] = useState(false);
  
  // WebSocket connection
  const wsUrl = 'ws://localhost:8080/dashboard';
  const {
    isConnected,
    messages,
    error,
    subscribeToMetrics,
    unsubscribeFromMetrics,
    getCurrentMetrics,
    sendPing
  } = useWebSocket(wsUrl);

  // Process incoming messages
  useEffect(() => {
    if (messages.length > 0) {
      const latestMessage = messages[messages.length - 1];
      
      switch (latestMessage.type) {
        case 'metrics_update':
          // Process metrics data for charts
          const chartData = DashboardService.processMetricsForChart(latestMessage);
          if (chartData) {
            setMetricsData(prev => {
              const newData = [...prev, chartData];
              // Keep only the last 50 data points
              return newData.slice(-50);
            });
          }
          
          // Format metrics for display
          const formatted = DashboardService.formatMetrics(latestMessage);
          setFormattedMetrics(formatted);
          break;
          
        case 'connection_ack':
          console.log('Connected to WebSocket server');
          break;
          
        case 'subscription_ack':
          setIsSubscribed(true);
          console.log('Subscribed to metrics updates');
          break;
          
        case 'unsubscription_ack':
          setIsSubscribed(false);
          console.log('Unsubscribed from metrics updates');
          break;
          
        case 'pong':
          console.log('Received pong response');
          break;
          
        case 'error':
          console.error('WebSocket error:', latestMessage.data);
          break;
          
        default:
          console.log('Unknown message type:', latestMessage.type);
      }
    }
  }, [messages]);

  // Handle subscription toggle
  const handleSubscriptionToggle = () => {
    if (isSubscribed) {
      unsubscribeFromMetrics();
    } else {
      subscribeToMetrics(1000); // Update every 1000ms
    }
  };

  // Handle refresh
  const handleRefresh = () => {
    getCurrentMetrics();
  };

  // Handle ping
  const handlePing = () => {
    sendPing();
  };

  return (
    <div className="dashboard">
      <header className="dashboard-header">
        <h1>Real-time Dashboard</h1>
        <ConnectionStatus 
          isConnected={isConnected} 
          isSubscribed={isSubscribed}
          error={error}
        />
      </header>
      
      <main className="dashboard-main">
        <ControlPanel
          isConnected={isConnected}
          isSubscribed={isSubscribed}
          onSubscriptionToggle={handleSubscriptionToggle}
          onRefresh={handleRefresh}
          onPing={handlePing}
        />
        
        <div className="metrics-grid">
          <div className="metric-card">
            <h3>CPU Usage</h3>
            <div className="metric-value">{formattedMetrics.cpuUsage || 'N/A'}</div>
          </div>
          
          <div className="metric-card">
            <h3>Memory Usage</h3>
            <div className="metric-value">{formattedMetrics.memoryUsage || 'N/A'}</div>
          </div>
          
          <div className="metric-card">
            <h3>Active Connections</h3>
            <div className="metric-value">{formattedMetrics.activeConnections || 0}</div>
          </div>
          
          <div className="metric-card">
            <h3>Network Traffic</h3>
            <div className="metric-value">{formattedMetrics.networkTraffic || 'N/A'}</div>
          </div>
          
          <div className="metric-card">
            <h3>Uptime</h3>
            <div className="metric-value">{formattedMetrics.uptime || 'N/A'}</div>
          </div>
        </div>
        
        <div className="charts-container">
          <div className="chart-wrapper">
            <h3>CPU Usage Over Time</h3>
            <MetricsChart 
              data={metricsData.map(d => d.cpu)} 
              chartType="cpu"
            />
          </div>
          
          <div className="chart-wrapper">
            <h3>Memory Usage Over Time</h3>
            <MetricsChart 
              data={metricsData.map(d => d.memory)} 
              chartType="memory"
            />
          </div>
          
          <div className="chart-wrapper">
            <h3>Active Connections Over Time</h3>
            <MetricsChart 
              data={metricsData.map(d => d.connections)} 
              chartType="connections"
            />
          </div>
        </div>
      </main>
    </div>
  );
};

export default Dashboard;