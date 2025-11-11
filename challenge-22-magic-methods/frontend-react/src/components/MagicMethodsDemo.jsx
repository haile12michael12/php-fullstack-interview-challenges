import React, { useState, useEffect } from 'react';
import DebugInfoCard from './DebugInfoCard';

const MagicMethodsDemo = () => {
  const [fluentData, setFluentData] = useState(null);
  const [proxyData, setProxyData] = useState(null);
  const [interceptorData, setInterceptorData] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const fetchFluentData = async () => {
    setLoading(true);
    setError(null);
    try {
      const response = await fetch('/api/magic/fluent');
      const data = await response.json();
      setFluentData(data);
    } catch (err) {
      setError('Failed to fetch fluent interface data');
    } finally {
      setLoading(false);
    }
  };

  const fetchProxyData = async () => {
    setLoading(true);
    setError(null);
    try {
      const response = await fetch('/api/magic/proxy');
      const data = await response.json();
      setProxyData(data);
    } catch (err) {
      setError('Failed to fetch proxy data');
    } finally {
      setLoading(false);
    }
  };

  const fetchInterceptorData = async () => {
    setLoading(true);
    setError(null);
    try {
      const response = await fetch('/api/magic/interceptor');
      const data = await response.json();
      setInterceptorData(data);
    } catch (err) {
      setError('Failed to fetch interceptor data');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchFluentData();
    fetchProxyData();
    fetchInterceptorData();
  }, []);

  return (
    <div className="magic-methods-demo">
      <h2>Magic Methods Demo</h2>
      
      {error && <div className="error">{error}</div>}
      {loading && <div className="loading">Loading...</div>}
      
      <div className="demo-section">
        <h3>Fluent Interface</h3>
        <button onClick={fetchFluentData}>Refresh</button>
        {fluentData && (
          <DebugInfoCard 
            title="Fluent Interface Results" 
            data={fluentData} 
          />
        )}
      </div>
      
      <div className="demo-section">
        <h3>Dynamic Proxy</h3>
        <button onClick={fetchProxyData}>Refresh</button>
        {proxyData && (
          <DebugInfoCard 
            title="Dynamic Proxy Results" 
            data={proxyData} 
          />
        )}
      </div>
      
      <div className="demo-section">
        <h3>Method Interceptor</h3>
        <button onClick={fetchInterceptorData}>Refresh</button>
        {interceptorData && (
          <DebugInfoCard 
            title="Method Interceptor Results" 
            data={interceptorData} 
          />
        )}
      </div>
    </div>
  );
};

export default MagicMethodsDemo;