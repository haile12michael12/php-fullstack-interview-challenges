import React, { useState, useEffect } from 'react';

const AbstractFactoryDemo = () => {
  const [factories, setFactories] = useState([]);
  const [selectedFactory, setSelectedFactory] = useState('');
  const [connectionResult, setConnectionResult] = useState(null);
  const [poolResult, setPoolResult] = useState(null);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    // Load available factories
    setFactories([
      { id: 'mysql', name: 'MySQL Factory' },
      { id: 'postgresql', name: 'PostgreSQL Factory' },
      { id: 'sqlite', name: 'SQLite Factory' }
    ]);
  }, []);

  const createConnection = async () => {
    if (!selectedFactory) return;
    
    setLoading(true);
    setConnectionResult(null);
    
    try {
      const response = await fetch(`/api/factory/${selectedFactory}`);
      const data = await response.json();
      setConnectionResult(data);
    } catch (error) {
      setConnectionResult({ status: 'error', message: error.message });
    } finally {
      setLoading(false);
    }
  };

  const testConnectionPool = async () => {
    setLoading(true);
    setPoolResult(null);
    
    try {
      const response = await fetch('/api/factory/pool');
      const data = await response.json();
      setPoolResult(data);
    } catch (error) {
      setPoolResult({ status: 'error', message: error.message });
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="abstract-factory-demo">
      <h2>Abstract Factory Pattern Demo</h2>
      
      <div className="factory-selector">
        <h3>Select Database Factory</h3>
        <select 
          value={selectedFactory} 
          onChange={(e) => setSelectedFactory(e.target.value)}
          disabled={loading}
        >
          <option value="">Choose a factory...</option>
          {factories.map(factory => (
            <option key={factory.id} value={factory.id}>
              {factory.name}
            </option>
          ))}
        </select>
        <button 
          onClick={createConnection} 
          disabled={!selectedFactory || loading}
        >
          Create Connection
        </button>
      </div>
      
      {loading && <div className="loading">Loading...</div>}
      
      {connectionResult && (
        <div className={`result ${connectionResult.status}`}>
          <h4>Connection Result</h4>
          <pre>{JSON.stringify(connectionResult, null, 2)}</pre>
        </div>
      )}
      
      <div className="pool-demo">
        <h3>Connection Pool Demo</h3>
        <button onClick={testConnectionPool} disabled={loading}>
          Test Connection Pool
        </button>
      </div>
      
      {poolResult && (
        <div className={`result ${poolResult.status}`}>
          <h4>Pool Result</h4>
          <pre>{JSON.stringify(poolResult, null, 2)}</pre>
        </div>
      )}
    </div>
  );
};

export default AbstractFactoryDemo;