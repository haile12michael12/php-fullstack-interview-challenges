import React from 'react';

const ErrorPage = ({ error, resetError }) => {
  return (
    <div className="error-page">
      <div className="error-container">
        <h1>Oops! Something went wrong</h1>
        <div className="error-details">
          <h2>Error Details:</h2>
          {error ? (
            <div>
              <p><strong>Message:</strong> {error.message}</p>
              {error.status && <p><strong>Status:</strong> {error.status}</p>}
              {error.correlationId && <p><strong>Correlation ID:</strong> {error.correlationId}</p>}
            </div>
          ) : (
            <p>An unexpected error occurred. Please try again later.</p>
          )}
        </div>
        <div className="error-actions">
          <button onClick={resetError} className="btn btn-primary">
            Try Again
          </button>
          <button onClick={() => window.location.reload()} className="btn btn-secondary">
            Refresh Page
          </button>
        </div>
      </div>
    </div>
  );
};

export default ErrorPage;