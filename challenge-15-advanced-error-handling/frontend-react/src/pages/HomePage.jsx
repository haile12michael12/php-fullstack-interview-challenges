import React from 'react';
import { useNavigate } from 'react-router-dom';

const HomePage = () => {
  const navigate = useNavigate();

  const errorScenarios = [
    { id: 'validation', name: 'Validation Error', path: '/error-demo/validation' },
    { id: 'database', name: 'Database Error', path: '/error-demo/database' },
    { id: 'authentication', name: 'Authentication Error', path: '/error-demo/authentication' },
    { id: 'authorization', name: 'Authorization Error', path: '/error-demo/authorization' },
    { id: 'external', name: 'External Service Error', path: '/error-demo/external' },
    { id: 'generic', name: 'Generic Error', path: '/error-demo/generic' },
  ];

  return (
    <div className="home-page">
      <div className="hero-section">
        <h1>Advanced Error Handling Demo</h1>
        <p>Explore different error scenarios and see how the application handles them.</p>
      </div>

      <div className="error-scenarios">
        <h2>Error Scenarios</h2>
        <div className="scenario-grid">
          {errorScenarios.map((scenario) => (
            <div key={scenario.id} className="scenario-card">
              <h3>{scenario.name}</h3>
              <button 
                className="btn btn-primary"
                onClick={() => navigate(scenario.path)}
              >
                Test Error
              </button>
            </div>
          ))}
        </div>
      </div>

      <div className="info-section">
        <h2>About This Demo</h2>
        <p>
          This application demonstrates advanced error handling techniques including:
        </p>
        <ul>
          <li>Comprehensive exception handling</li>
          <li>Retry mechanisms with exponential backoff</li>
          <li>Circuit breaker patterns</li>
          <li>Fallback strategies</li>
          <li>Structured logging with correlation IDs</li>
          <li>Health monitoring and reporting</li>
        </ul>
      </div>
    </div>
  );
};

export default HomePage;