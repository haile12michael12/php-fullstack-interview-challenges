import React from 'react';

const ConnectionStatus = ({ isConnected, isSubscribed, error }) => {
  return (
    <div className="connection-status">
      <div className={`status-indicator ${isConnected ? 'connected' : 'disconnected'}`}>
        <span className="status-dot"></span>
        <span className="status-text">
          {isConnected ? 'Connected' : 'Disconnected'}
        </span>
      </div>
      
      {isConnected && (
        <div className={`subscription-status ${isSubscribed ? 'subscribed' : 'unsubscribed'}`}>
          <span className="subscription-dot"></span>
          <span className="subscription-text">
            {isSubscribed ? 'Subscribed to metrics' : 'Not subscribed'}
          </span>
        </div>
      )}
      
      {error && (
        <div className="error-status">
          <span className="error-text">Error: {error.message}</span>
        </div>
      )}
    </div>
  );
};

export default ConnectionStatus;