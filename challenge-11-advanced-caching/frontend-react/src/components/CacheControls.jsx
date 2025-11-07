import React, { useState } from 'react';
import { cacheService } from '../services/cacheService';

const CacheControls = () => {
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState('');

  const handleWarmCache = async () => {
    setLoading(true);
    setMessage('');
    try {
      await cacheService.warmCache();
      setMessage('Cache warming initiated successfully');
    } catch (error) {
      setMessage(`Error: ${error.message}`);
    } finally {
      setLoading(false);
    }
  };

  const handleFlushCache = async () => {
    if (!window.confirm('Are you sure you want to flush all cache data?')) {
      return;
    }
    
    setLoading(true);
    setMessage('');
    try {
      await cacheService.flushCache();
      setMessage('Cache flushed successfully');
    } catch (error) {
      setMessage(`Error: ${error.message}`);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="cache-controls">
      <div className="control-group">
        <button 
          onClick={handleWarmCache} 
          disabled={loading}
          className="btn btn-primary"
        >
          {loading ? 'Warming...' : 'Warm Cache'}
        </button>
        
        <button 
          onClick={handleFlushCache} 
          disabled={loading}
          className="btn btn-danger"
        >
          {loading ? 'Flushing...' : 'Flush Cache'}
        </button>
      </div>
      
      {message && (
        <div className={`message ${message.includes('Error') ? 'error' : 'success'}`}>
          {message}
        </div>
      )}
    </div>
  );
};

export default CacheControls;