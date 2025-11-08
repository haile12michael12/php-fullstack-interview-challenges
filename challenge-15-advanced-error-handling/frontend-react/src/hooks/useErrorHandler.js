import { useCallback } from 'react';
import { useErrorContext } from '../context/ErrorContext';
import errorService from '../services/errorService';

/**
 * Custom hook for handling errors in components
 * Provides convenient methods for error handling and reporting
 */
const useErrorHandler = () => {
  const { addError, setGlobalError, clearGlobalError } = useErrorContext();

  /**
   * Handle an API error
   * @param {Error} error - The error object
   * @param {Object} context - Additional context about where the error occurred
   */
  const handleApiError = useCallback((error, context = {}) => {
    const errorInfo = errorService.handleApiError(error, context);
    
    if (errorInfo) {
      addError({
        type: 'API_ERROR',
        ...errorInfo,
      });
    }
    
    return errorInfo;
  }, [addError]);

  /**
   * Handle a general application error
   * @param {Error} error - The error object
   * @param {Object} context - Additional context about where the error occurred
   */
  const handleError = useCallback((error, context = {}) => {
    const errorInfo = {
      message: error.message,
      stack: error.stack,
      context,
      timestamp: new Date().toISOString(),
    };

    errorService.handleError(error, context);
    addError({
      type: 'APPLICATION_ERROR',
      ...errorInfo,
    });

    return errorInfo;
  }, [addError]);

  /**
   * Set a global error (e.g., for error boundaries)
   * @param {Error} error - The error object
   */
  const setGlobalAppError = useCallback((error) => {
    const errorInfo = {
      message: error.message,
      stack: error.stack,
      timestamp: new Date().toISOString(),
    };

    setGlobalError(errorInfo);
    errorService.handleGlobalError(error);
  }, [setGlobalError]);

  /**
   * Clear the global error
   */
  const clearGlobalAppError = useCallback(() => {
    clearGlobalError();
  }, [clearGlobalError]);

  return {
    handleApiError,
    handleError,
    setGlobalAppError,
    clearGlobalAppError,
  };
};

export default useErrorHandler;