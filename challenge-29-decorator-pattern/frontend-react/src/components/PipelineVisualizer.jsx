import React, { useState, useEffect } from 'react';
import middlewareService from '../services/middlewareService';

const PipelineVisualizer = () => {
  const [middlewareMetrics, setMiddlewareMetrics] = useState({});
  const [pipelineConfig, setPipelineConfig] = useState({});
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchPipelineData();
  }, []);

  const fetchPipelineData = async () => {
    setLoading(true);
    setError(null);
    
    try {
      const [metrics, config] = await Promise.all([
        middlewareService.getMiddlewareMetrics(),
        middlewareService.getPipelineConfig()
      ]);
      
      setMiddlewareMetrics(metrics);
      setPipelineConfig(config);
    } catch (err) {
      setError('Failed to load pipeline data');
    } finally {
      setLoading(false);
    }
  };

  const getMetricValue = (middlewareId, metricKey) => {
    if (middlewareMetrics[middlewareId] && middlewareMetrics[middlewareId][metricKey] !== undefined) {
      return middlewareMetrics[middlewareId][metricKey];
    }
    return 'N/A';
  };

  const getSuccessRateColor = (rate) => {
    if (rate >= 99) return '#4CAF50'; // Green
    if (rate >= 95) return '#FFC107'; // Yellow
    return '#F44336'; // Red
  };

  return (
    <div className="pipeline-visualizer">
      <div className="header">
        <h2>Middleware Pipeline Visualization</h2>
        <button onClick={fetchPipelineData} disabled={loading} className="refresh-button">
          {loading ? 'Refreshing...' : 'Refresh Data'}
        </button>
      </div>

      {error && <div className="error-message">{error}</div>}

      <div className="visualization-section">
        <h3>Middleware Chain</h3>
        <div className="pipeline-diagram">
          <div className="pipeline-flow">
            {/* Entry point */}
            <div className="pipeline-node entry">
              <div className="node-content">
                <h4>Client Request</h4>
              </div>
            </div>
            
            {/* Middleware nodes */}
            {Object.keys(middlewareMetrics).map((middlewareId, index) => (
              <React.Fragment key={middlewareId}>
                <div className="pipeline-arrow">
                  <div className="arrow-line"></div>
                  <div className="arrow-head"></div>
                </div>
                <div className="pipeline-node middleware">
                  <div className="node-content">
                    <h4>{middlewareId.charAt(0).toUpperCase() + middlewareId.slice(1)}</h4>
                    <div className="metrics">
                      <div className="metric">
                        <span className="metric-label">Requests:</span>
                        <span className="metric-value">{getMetricValue(middlewareId, 'requests')}</span>
                      </div>
                      {middlewareMetrics[middlewareId].success_rate !== undefined && (
                        <div className="metric">
                          <span className="metric-label">Success:</span>
                          <span 
                            className="metric-value" 
                            style={{color: getSuccessRateColor(getMetricValue(middlewareId, 'success_rate'))}}
                          >
                            {getMetricValue(middlewareId, 'success_rate')}%
                          </span>
                        </div>
                      )}
                      {middlewareMetrics[middlewareId].avg_response_time !== undefined && (
                        <div className="metric">
                          <span className="metric-label">Avg Time:</span>
                          <span className="metric-value">{getMetricValue(middlewareId, 'avg_response_time')}ms</span>
                        </div>
                      )}
                    </div>
                  </div>
                </div>
              </React.Fragment>
            ))}
            
            <div className="pipeline-arrow">
              <div className="arrow-line"></div>
              <div className="arrow-head"></div>
            </div>
            
            {/* Final handler */}
            <div className="pipeline-node final">
              <div className="node-content">
                <h4>Final Handler</h4>
              </div>
            </div>
            
            <div className="pipeline-arrow">
              <div className="arrow-line"></div>
              <div className="arrow-head"></div>
            </div>
            
            {/* Response */}
            <div className="pipeline-node response">
              <div className="node-content">
                <h4>Client Response</h4>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="visualization-section">
        <h3>Pipeline Configuration</h3>
        <div className="config-details">
          <div className="config-item">
            <h4>Default Middleware</h4>
            <div className="config-value">
              {pipelineConfig.default_middleware?.map(middleware => (
                <span key={middleware} className="tag">{middleware}</span>
              )) || 'None configured'}
            </div>
          </div>
          
          <div className="config-item">
            <h4>Auth Required Endpoints</h4>
            <div className="config-value">
              {pipelineConfig.auth_required_endpoints?.map(endpoint => (
                <span key={endpoint} className="tag">{endpoint}</span>
              )) || 'None configured'}
            </div>
          </div>
          
          <div className="config-item">
            <h4>Rate Limit Settings</h4>
            <div className="config-value">
              {pipelineConfig.rate_limit_settings ? (
                <div>
                  <p>Max Requests: {pipelineConfig.rate_limit_settings.max_requests}</p>
                  <p>Time Window: {pipelineConfig.rate_limit_settings.time_window}s</p>
                </div>
              ) : (
                'Not configured'
              )}
            </div>
          </div>
        </div>
      </div>

      <div className="visualization-section">
        <h3>Performance Metrics</h3>
        <div className="metrics-grid">
          {Object.entries(middlewareMetrics).map(([middlewareId, metrics]) => (
            <div key={middlewareId} className="metric-card">
              <h4>{middlewareId.charAt(0).toUpperCase() + middlewareId.slice(1)} Metrics</h4>
              <div className="metric-details">
                {Object.entries(metrics).map(([key, value]) => (
                  <div key={key} className="metric-row">
                    <span className="metric-key">{key.replace(/_/g, ' ')}:</span>
                    <span className="metric-value">
                      {typeof value === 'number' ? 
                        (key.includes('rate') ? `${value.toFixed(1)}%` : 
                         key.includes('time') ? `${value.toFixed(1)}ms` : 
                         value) : 
                        value}
                    </span>
                  </div>
                ))}
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default PipelineVisualizer;