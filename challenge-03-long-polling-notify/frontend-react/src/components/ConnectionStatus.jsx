import React from 'react';

function ConnectionStatus({ status, serverStatus }) {
  const getStatusColor = () => {
    switch (status) {
      case 'connected':
        return 'green';
      case 'connecting':
        return 'orange';
      case 'error':
        return 'red';
      default:
        return 'gray';
    }
  };

  const getStatusText = () => {
    switch (status) {
      case 'connected':
        return 'Connected';
      case 'connecting':
        return 'Connecting...';
      case 'error':
        return 'Connection Error';
      default:
        return 'Disconnected';
    }
  };

  return (
    <div className="connection-status">
      <h2>Connection Status</h2>
      <div className="status-indicator">
        <span 
          className="status-dot" 
          style={{ backgroundColor: getStatusColor() }}
        ></span>
        <span>{getStatusText()}</span>
      </div>
      
      {serverStatus && (
        <div className="server-info">
          <p>Active Connections: {serverStatus.active_connections}</p>
          <p>Pending Messages: {serverStatus.pending_messages}</p>
        </div>
      )}
    </div>
  );
}

export default ConnectionStatus;