import React, { useState, useEffect } from 'react';
import commandService from '../services/commandService';

const CommandQueue = () => {
  const [queueStatus, setQueueStatus] = useState({ size: 0, isEmpty: true, isFull: false });
  const [commandType, setCommandType] = useState('create-file');
  const [commandData, setCommandData] = useState({
    filename: '',
    content: '',
    to: '',
    subject: '',
    body: '',
    table: '',
    operation: 'insert',
    data: ''
  });
  const [loading, setLoading] = useState(false);
  const [result, setResult] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchQueueStatus();
  }, []);

  const fetchQueueStatus = async () => {
    try {
      const status = await commandService.getQueueStatus();
      setQueueStatus(status);
    } catch (err) {
      setError('Failed to fetch queue status');
    }
  };

  const handleCommandDataChange = (field, value) => {
    setCommandData(prev => ({
      ...prev,
      [field]: value
    }));
  };

  const handleQueueCommand = async () => {
    setLoading(true);
    setError(null);
    setResult(null);

    try {
      // Prepare command data based on command type
      let data = { type: commandType };
      
      switch (commandType) {
        case 'create-file':
          data.filename = commandData.filename;
          data.content = commandData.content;
          break;
        case 'delete-file':
          data.filename = commandData.filename;
          break;
        case 'send-email':
          data.to = commandData.to;
          data.subject = commandData.subject;
          data.body = commandData.body;
          break;
        case 'database':
          data.table = commandData.table;
          data.operation = commandData.operation;
          try {
            data.data = JSON.parse(commandData.data || '{}');
          } catch (e) {
            throw new Error('Invalid JSON in data field');
          }
          break;
        default:
          throw new Error('Invalid command type');
      }

      const response = await commandService.queueCommand(data);
      setResult(response);
      fetchQueueStatus(); // Refresh queue status
    } catch (err) {
      setError(err.message || 'Failed to queue command');
    } finally {
      setLoading(false);
    }
  };

  const renderCommandForm = () => {
    switch (commandType) {
      case 'create-file':
        return (
          <div className="command-form">
            <div className="form-group">
              <label htmlFor="filename">Filename:</label>
              <input
                type="text"
                id="filename"
                value={commandData.filename}
                onChange={(e) => handleCommandDataChange('filename', e.target.value)}
                placeholder="example.txt"
              />
            </div>
            <div className="form-group">
              <label htmlFor="content">Content:</label>
              <textarea
                id="content"
                value={commandData.content}
                onChange={(e) => handleCommandDataChange('content', e.target.value)}
                placeholder="File content"
                rows="4"
              />
            </div>
          </div>
        );
      
      case 'delete-file':
        return (
          <div className="command-form">
            <div className="form-group">
              <label htmlFor="filename">Filename:</label>
              <input
                type="text"
                id="filename"
                value={commandData.filename}
                onChange={(e) => handleCommandDataChange('filename', e.target.value)}
                placeholder="example.txt"
              />
            </div>
          </div>
        );
      
      case 'send-email':
        return (
          <div className="command-form">
            <div className="form-group">
              <label htmlFor="to">To:</label>
              <input
                type="email"
                id="to"
                value={commandData.to}
                onChange={(e) => handleCommandDataChange('to', e.target.value)}
                placeholder="recipient@example.com"
              />
            </div>
            <div className="form-group">
              <label htmlFor="subject">Subject:</label>
              <input
                type="text"
                id="subject"
                value={commandData.subject}
                onChange={(e) => handleCommandDataChange('subject', e.target.value)}
                placeholder="Email subject"
              />
            </div>
            <div className="form-group">
              <label htmlFor="body">Body:</label>
              <textarea
                id="body"
                value={commandData.body}
                onChange={(e) => handleCommandDataChange('body', e.target.value)}
                placeholder="Email body"
                rows="4"
              />
            </div>
          </div>
        );
      
      case 'database':
        return (
          <div className="command-form">
            <div className="form-group">
              <label htmlFor="table">Table:</label>
              <input
                type="text"
                id="table"
                value={commandData.table}
                onChange={(e) => handleCommandDataChange('table', e.target.value)}
                placeholder="users"
              />
            </div>
            <div className="form-group">
              <label htmlFor="operation">Operation:</label>
              <select
                id="operation"
                value={commandData.operation}
                onChange={(e) => handleCommandDataChange('operation', e.target.value)}
              >
                <option value="insert">Insert</option>
                <option value="update">Update</option>
                <option value="delete">Delete</option>
              </select>
            </div>
            <div className="form-group">
              <label htmlFor="data">Data (JSON):</label>
              <textarea
                id="data"
                value={commandData.data}
                onChange={(e) => handleCommandDataChange('data', e.target.value)}
                placeholder='{"name": "John", "email": "john@example.com"}'
                rows="4"
              />
            </div>
          </div>
        );
      
      default:
        return <div>Select a command type</div>;
    }
  };

  return (
    <div className="command-queue">
      <h2>Command Queue</h2>
      
      <div className="queue-status">
        <h3>Queue Status</h3>
        <div className="status-info">
          <p>Queue Size: {queueStatus.size}</p>
          <p>Status: {queueStatus.isEmpty ? 'Empty' : queueStatus.isFull ? 'Full' : 'Active'}</p>
        </div>
      </div>
      
      <div className="queue-command">
        <h3>Queue New Command</h3>
        <div className="form-group">
          <label htmlFor="commandType">Command Type:</label>
          <select
            id="commandType"
            value={commandType}
            onChange={(e) => setCommandType(e.target.value)}
          >
            <option value="create-file">Create File</option>
            <option value="delete-file">Delete File</option>
            <option value="send-email">Send Email</option>
            <option value="database">Database Operation</option>
          </select>
        </div>
        
        {renderCommandForm()}
        
        <button 
          onClick={handleQueueCommand} 
          disabled={loading}
          className="queue-button"
        >
          {loading ? 'Queuing...' : 'Queue Command'}
        </button>
      </div>
      
      {error && <div className="error-message">{error}</div>}
      
      {result && (
        <div className="result-message success">
          <h4>Command Queued Successfully!</h4>
          <p>{result.message}</p>
        </div>
      )}
    </div>
  );
};

export default CommandQueue;