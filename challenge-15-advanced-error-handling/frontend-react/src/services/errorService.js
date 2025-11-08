// errorService.js - Error handling and reporting service

import { ApiError } from './apiClient';
import logger from '../utils/logger';

class ErrorService {
  constructor() {
    this.errorListeners = [];
  }

  // Handle API errors
  handleApiError(error, context = {}) {
    if (!(error instanceof ApiError)) {
      logger.error('Non-API error occurred', { error, context });
      return;
    }

    const errorInfo = {
      message: error.message,
      status: error.status,
      correlationId: error.correlationId,
      data: error.data,
      context,
      timestamp: new Date().toISOString(),
    };

    // Log the error
    logger.error('API Error', errorInfo);

    // Notify listeners
    this.notifyErrorListeners(errorInfo);

    // Handle specific error types
    switch (error.status) {
      case 401:
        this.handleUnauthorizedError(errorInfo);
        break;
      case 403:
        this.handleForbiddenError(errorInfo);
        break;
      case 422:
        this.handleValidationError(errorInfo);
        break;
      case 500:
        this.handleServerError(errorInfo);
        break;
      case 502:
      case 503:
        this.handleServiceUnavailableError(errorInfo);
        break;
      default:
        this.handleGenericError(errorInfo);
    }

    return errorInfo;
  }

  // Handle JavaScript errors
  handleJsError(error, context = {}) {
    const errorInfo = {
      message: error.message,
      stack: error.stack,
      context,
      timestamp: new Date().toISOString(),
    };

    logger.error('JavaScript Error', errorInfo);
    this.notifyErrorListeners(errorInfo);

    return errorInfo;
  }

  // Handle promise rejections
  handlePromiseRejection(reason, context = {}) {
    const errorInfo = {
      message: reason.message || 'Unhandled promise rejection',
      reason,
      context,
      timestamp: new Date().toISOString(),
    };

    logger.error('Promise Rejection', errorInfo);
    this.notifyErrorListeners(errorInfo);

    return errorInfo;
  }

  // Add error listener
  addErrorListener(callback) {
    this.errorListeners.push(callback);
  }

  // Remove error listener
  removeErrorListener(callback) {
    const index = this.errorListeners.indexOf(callback);
    if (index > -1) {
      this.errorListeners.splice(index, 1);
    }
  }

  // Notify all error listeners
  notifyErrorListeners(errorInfo) {
    this.errorListeners.forEach(callback => {
      try {
        callback(errorInfo);
      } catch (e) {
        logger.error('Error in error listener', { listenerError: e, originalError: errorInfo });
      }
    });
  }

  // Specific error handlers
  handleUnauthorizedError(errorInfo) {
    // Redirect to login or show auth error
    logger.warn('Unauthorized access attempt', errorInfo);
  }

  handleForbiddenError(errorInfo) {
    // Show permission denied message
    logger.warn('Access forbidden', errorInfo);
  }

  handleValidationError(errorInfo) {
    // Show validation errors to user
    logger.warn('Validation error', errorInfo);
  }

  handleServerError(errorInfo) {
    // Show generic server error message
    logger.error('Server error', errorInfo);
  }

  handleServiceUnavailableError(errorInfo) {
    // Show service unavailable message
    logger.error('Service unavailable', errorInfo);
  }

  handleGenericError(errorInfo) {
    // Handle other errors
    logger.error('Generic error', errorInfo);
  }

  // Report error to external service (e.g., Sentry, Bugsnag)
  reportError(errorInfo) {
    // In a real implementation, this would send the error to an external service
    logger.info('Error reported to external service', errorInfo);
  }
}

export default new ErrorService();