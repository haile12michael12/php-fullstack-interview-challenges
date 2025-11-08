import React, { useState, useEffect } from 'react';

const NotificationToast = ({ message, type = 'info', duration = 5000, onClose }) => {
  const [isVisible, setIsVisible] = useState(true);

  useEffect(() => {
    const timer = setTimeout(() => {
      setIsVisible(false);
      if (onClose) onClose();
    }, duration);

    return () => clearTimeout(timer);
  }, [duration, onClose]);

  if (!isVisible) return null;

  const getTypeClass = () => {
    switch (type) {
      case 'success': return 'toast-success';
      case 'warning': return 'toast-warning';
      case 'error': return 'toast-error';
      default: return 'toast-info';
    }
  };

  return (
    <div className={`notification-toast ${getTypeClass()}`}>
      <div className="toast-content">
        <span className="toast-message">{message}</span>
        <button className="toast-close" onClick={() => {
          setIsVisible(false);
          if (onClose) onClose();
        }}>
          ×
        </button>
      </div>
    </div>
  );
};

export default NotificationToast;