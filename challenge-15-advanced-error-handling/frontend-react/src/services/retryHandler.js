// Retry handler for API calls with exponential backoff
class RetryHandler {
  constructor(options = {}) {
    this.maxRetries = options.maxRetries || 3;
    this.baseDelay = options.baseDelay || 1000;
    this.maxDelay = options.maxDelay || 10000;
    this.exponentialFactor = options.exponentialFactor || 2;
  }

  // Calculate delay with exponential backoff and jitter
  calculateDelay(attempt) {
    const delay = Math.min(
      this.baseDelay * Math.pow(this.exponentialFactor, attempt),
      this.maxDelay
    );
    // Add jitter to prevent thundering herd
    return delay * (0.5 + Math.random() * 0.5);
  }

  // Execute a function with retry logic
  async execute(fn, shouldRetry = () => true) {
    let lastError;
    
    for (let attempt = 0; attempt <= this.maxRetries; attempt++) {
      try {
        const result = await fn();
        return result;
      } catch (error) {
        lastError = error;
        
        // Check if we should retry this error
        if (!shouldRetry(error) || attempt === this.maxRetries) {
          throw error;
        }
        
        // Wait before retrying
        const delay = this.calculateDelay(attempt);
        await new Promise(resolve => setTimeout(resolve, delay));
      }
    }
    
    throw lastError;
  }

  // Retry a fetch request
  async fetchWithRetry(url, options = {}) {
    return this.execute(
      () => fetch(url, options),
      (error) => {
        // Retry on network errors or 5xx status codes
        return error instanceof TypeError || 
               (error.status && error.status >= 500);
      }
    );
  }
}

export default RetryHandler;