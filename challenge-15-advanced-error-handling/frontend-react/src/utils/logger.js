/**
 * Frontend logger utility with different log levels
 * Provides structured logging with context and correlation IDs
 */

class Logger {
  constructor(options = {}) {
    this.level = options.level || 'info';
    this.correlationId = null;
  }

  // Log levels in order of severity
  static LEVELS = {
    ERROR: 0,
    WARN: 1,
    INFO: 2,
    DEBUG: 3,
  };

  // Check if a log level should be output based on the current level
  shouldLog(level) {
    return Logger.LEVELS[level] <= Logger.LEVELS[this.level];
  }

  // Set correlation ID for request tracking
  setCorrelationId(id) {
    this.correlationId = id;
  }

  // Clear correlation ID
  clearCorrelationId() {
    this.correlationId = null;
  }

  // Format log message with timestamp, level, and context
  formatMessage(level, message, context = {}) {
    const timestamp = new Date().toISOString();
    const correlationInfo = this.correlationId ? `[${this.correlationId}]` : '';
    
    const baseMessage = `${timestamp} ${correlationInfo}[${level.toUpperCase()}] ${message}`;
    
    if (Object.keys(context).length > 0) {
      return `${baseMessage} | Context: ${JSON.stringify(context)}`;
    }
    
    return baseMessage;
  }

  // Log an error message
  error(message, context = {}) {
    if (!this.shouldLog('ERROR')) return;
    
    const formattedMessage = this.formatMessage('ERROR', message, context);
    console.error(formattedMessage);
    
    // In production, you might send this to a logging service
    this.sendToLoggingService('ERROR', message, context);
  }

  // Log a warning message
  warn(message, context = {}) {
    if (!this.shouldLog('WARN')) return;
    
    const formattedMessage = this.formatMessage('WARN', message, context);
    console.warn(formattedMessage);
    
    this.sendToLoggingService('WARN', message, context);
  }

  // Log an info message
  info(message, context = {}) {
    if (!this.shouldLog('INFO')) return;
    
    const formattedMessage = this.formatMessage('INFO', message, context);
    console.info(formattedMessage);
    
    this.sendToLoggingService('INFO', message, context);
  }

  // Log a debug message
  debug(message, context = {}) {
    if (!this.shouldLog('DEBUG')) return;
    
    const formattedMessage = this.formatMessage('DEBUG', message, context);
    console.debug(formattedMessage);
    
    this.sendToLoggingService('DEBUG', message, context);
  }

  // Send log to external logging service (placeholder)
  sendToLoggingService(level, message, context) {
    // In a real application, this would send logs to a backend service
    // For now, we'll just check if we're in production
    if (process.env.NODE_ENV === 'production') {
      // Example implementation:
      // fetch('/api/logs', {
      //   method: 'POST',
      //   headers: {
      //     'Content-Type': 'application/json',
      //   },
      //   body: JSON.stringify({
      //     level,
      //     message,
      //     context,
      //     timestamp: new Date().toISOString(),
      //     correlationId: this.correlationId,
      //     userAgent: navigator.userAgent,
      //     url: window.location.href,
      //   }),
      // }).catch(() => {
      //   // Ignore logging errors to prevent infinite loops
      // });
    }
  }

  // Create a child logger with additional context
  child(context) {
    const childLogger = new Logger({ level: this.level });
    childLogger.correlationId = this.correlationId;
    childLogger.parentContext = context;
    return childLogger;
  }
}

// Create and export a default logger instance
const logger = new Logger({
  level: process.env.NODE_ENV === 'production' ? 'warn' : 'debug',
});

export default logger;