import React, { useState, useEffect } from 'react';
import middlewareService from '../services/middlewareService';

const MiddlewareDemo = () => {
  const [availableMiddleware, setAvailableMiddleware] = useState([]);
  const [selectedMiddleware, setSelectedMiddleware] = useState([]);
  const [requestData, setRequestData] = useState({
    method: 'GET',
    uri: '/api/test',
    headers: {},
    body: '',
    query_params: {}
  });
  const [response, setResponse] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchAvailableMiddleware();
  }, []);

  const fetchAvailableMiddleware = async () => {
    try {
      const middleware = await middlewareService.getAvailableMiddleware();
      setAvailableMiddleware(middleware);
    } catch (err) {
      setError('Failed to load available middleware');
    }
  };

  const handleMiddlewareToggle = (middlewareId) => {
    setSelectedMiddleware(prev => {
      if (prev.includes(middlewareId)) {
        return prev.filter(id => id !== middlewareId);
      } else {
        return [...prev, middlewareId];
      }
    });
  };

  const handleRequestDataChange = (field, value) => {
    setRequestData(prev => ({
      ...prev,
      [field]: value
    }));
  };

  const handleProcessRequest = async () => {
    if (selectedMiddleware.length === 0) {
      setError('Please select at least one middleware');
      return;
    }

    setLoading(true);
    setError(null);
    setResponse(null);

    try {
      const result = await middlewareService.processRequest(selectedMiddleware, requestData);
      setResponse(result);
    } catch (err) {
      setError('Failed to process request');
    } finally {
      setLoading(false);
    }
  };

  const handleApplyMiddleware = async () => {
    if (selectedMiddleware.length === 0) {
      setError('Please select at least one middleware');
      return;
    }

    setLoading(true);
    setError(null);

    try {
      const result = await middlewareService.applyMiddleware(selectedMiddleware);
      setResponse({
        status_code: 200,
        body: JSON.stringify({ applied_middleware: result }, null, 2)
      });
    } catch (err) {
      setError('Failed to apply middleware');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="middleware-demo">
      <h2>Middleware Pipeline Demo</h2>
      
      <div className="demo-section">
        <h3>Available Middleware</h3>
        <div className="middleware-list">
          {availableMiddleware.map(middleware => (
            <div 
              key={middleware.id} 
              className={`middleware-item ${selectedMiddleware.includes(middleware.id) ? 'selected' : ''}`}
              onClick={() => handleMiddlewareToggle(middleware.id)}
            >
              <h4>{middleware.name}</h4>
              <p>{middleware.description}</p>
            </div>
          ))}
        </div>
      </div>

      <div className="demo-section">
        <h3>Request Configuration</h3>
        <div className="form-group">
          <label>Method:</label>
          <select 
            value={requestData.method} 
            onChange={(e) => handleRequestDataChange('method', e.target.value)}
          >
            <option value="GET">GET</option>
            <option value="POST">POST</option>
            <option value="PUT">PUT</option>
            <option value="DELETE">DELETE</option>
          </select>
        </div>
        
        <div className="form-group">
          <label>URI:</label>
          <input 
            type="text" 
            value={requestData.uri} 
            onChange={(e) => handleRequestDataChange('uri', e.target.value)}
            placeholder="/api/test"
          />
        </div>
        
        <div className="form-group">
          <label>Body (JSON):</label>
          <textarea 
            value={requestData.body} 
            onChange={(e) => handleRequestDataChange('body', e.target.value)}
            placeholder='{"key": "value"}'
            rows="4"
          />
        </div>
      </div>

      <div className="actions">
        <button 
          onClick={handleProcessRequest} 
          disabled={loading || selectedMiddleware.length === 0}
          className="primary-button"
        >
          {loading ? 'Processing...' : 'Process Request'}
        </button>
        <button 
          onClick={handleApplyMiddleware} 
          disabled={loading || selectedMiddleware.length === 0}
          className="secondary-button"
        >
          Apply Middleware Only
        </button>
      </div>

      {error && <div className="error-message">{error}</div>}
      
      {response && (
        <div className="demo-section">
          <h3>Response</h3>
          <div className="response-display">
            <div className="response-header">
              <span className="status-code">Status: {response.status_code}</span>
            </div>
            <div className="response-body">
              <pre>{response.body || 'No response body'}</pre>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default MiddlewareDemo;