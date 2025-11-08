import React from 'react';

const ControlPanel = ({ 
  isConnected, 
  isSubscribed, 
  onSubscriptionToggle, 
  onRefresh, 
  onPing 
}) => {
  return (
    <div className="control-panel">
      <div className="control-group">
        <button 
          onClick={onSubscriptionToggle}
          disabled={!isConnected}
          className={isSubscribed ? 'unsubscribe-btn' : 'subscribe-btn'}
        >
          {isSubscribed ? 'Stop Metrics' : 'Start Metrics'}
        </button>
        
        <button 
          onClick={onRefresh}
          disabled={!isConnected}
          className="refresh-btn"
        >
          Refresh Data
        </button>
        
        <button 
          onClick={onPing}
          disabled={!isConnected}
          className="ping-btn"
        >
          Ping Server
        </button>
      </div>
      
      <div className="control-info">
        <p>
          {isConnected 
            ? 'WebSocket connection established' 
            : 'WebSocket connection disconnected'}
        </p>
        <p>
          {isSubscribed 
            ? 'Real-time metrics updates enabled' 
            : 'Real-time metrics updates disabled'}
        </p>
      </div>
    </div>
  );
};

export default ControlPanel;