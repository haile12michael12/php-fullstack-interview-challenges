import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import ErrorPage from '../components/ErrorPage';
import FallbackView from '../components/FallbackView';
import NotificationToast from '../components/NotificationToast';

const ErrorDemoPage = () => {
  const { errorType } = useParams();
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(true);
  const [showToast, setShowToast] = useState(false);
  const [toastMessage, setToastMessage] = useState('');

  useEffect(() => {
    // Simulate API call that might fail
    const simulateApiCall = async () => {
      setLoading(true);
      setError(null);
      
      try {
        // Simulate different error scenarios
        switch (errorType) {
          case 'validation':
            throw new Error('Validation failed: Email is required');
          case 'database':
            throw new Error('Database connection failed');
          case 'authentication':
            throw new Error('Authentication failed: Invalid credentials');
          case 'authorization':
            throw new Error('Authorization failed: Insufficient permissions');
          case 'external':
            throw new Error('External service unavailable');
          case 'generic':
            throw new Error('An unexpected error occurred');
          default:
            throw new Error('Unknown error type');
        }
      } catch (err) {
        setError({
          message: err.message,
          status: 500,
          correlationId: 'corr-' + Math.random().toString(36).substr(2, 9)
        });
        
        // Show notification
        setToastMessage(`Error occurred: ${err.message}`);
        setShowToast(true);
      } finally {
        setLoading(false);
      }
    };

    simulateApiCall();
  }, [errorType]);

  const resetError = () => {
    setError(null);
    setLoading(true);
    
    // Re-simulate the API call
    setTimeout(() => {
      setLoading(false);
      setError({
        message: 'Error was reset but still occurs',
        status: 500,
        correlationId: 'corr-' + Math.random().toString(36).substr(2, 9)
      });
    }, 1000);
  };

  if (loading) {
    return <FallbackView message="Loading error scenario..." />;
  }

  if (error) {
    return (
      <>
        {showToast && (
          <NotificationToast 
            message={toastMessage} 
            type="error" 
            onClose={() => setShowToast(false)} 
          />
        )}
        <ErrorPage error={error} resetError={resetError} />
      </>
    );
  }

  return (
    <div className="error-demo-page">
      <h1>Error Demo: {errorType}</h1>
      <p>This scenario completed without errors.</p>
    </div>
  );
};

export default ErrorDemoPage;