import React from 'react';
import Card from '../ui/Card.jsx';
import Button from '../ui/Button.jsx';

const LogViewer = ({ log }) => {
  if (!log) {
    return null;
  }

  const formatJson = (obj) => {
    return JSON.stringify(obj, null, 2);
  };

  const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text);
  };

  return (
    <div className="log-viewer">
      <div className="log-header">
        <h2>Log Details</h2>
        <Button onClick={() => copyToClipboard(formatJson(log))}>
          Copy to Clipboard
        </Button>
      </div>
      
      <Card className="log-details">
        <div className="log-field">
          <label>ID:</label>
          <span>{log.id}</span>
        </div>
        
        <div className="log-field">
          <label>Level:</label>
          <span className={`level-${log.level}`}>{log.level.toUpperCase()}</span>
        </div>
        
        <div className="log-field">
          <label>Timestamp:</label>
          <span>{log.timestamp}</span>
        </div>
        
        <div className="log-field">
          <label>Message:</label>
          <span>{log.message}</span>
        </div>
        
        <div className="log-field">
          <label>IP Address:</label>
          <span>{log.ip_address}</span>
        </div>
        
        <div className="log-field">
          <label>User Agent:</label>
          <span>{log.user_agent}</span>
        </div>
        
        {log.context && (
          <div className="log-field">
            <label>Context:</label>
            <pre className="log-context">{formatJson(log.context)}</pre>
          </div>
        )}
      </Card>
    </div>
  );
};

export default LogViewer;