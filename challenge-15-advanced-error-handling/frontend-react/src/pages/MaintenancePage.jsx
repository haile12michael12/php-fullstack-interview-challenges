import React, { useState, useEffect } from 'react';

const MaintenancePage = () => {
  const [timeLeft, setTimeLeft] = useState({
    hours: 1,
    minutes: 30,
    seconds: 0
  });

  useEffect(() => {
    const timer = setInterval(() => {
      setTimeLeft(prev => {
        if (prev.seconds > 0) {
          return { ...prev, seconds: prev.seconds - 1 };
        } else if (prev.minutes > 0) {
          return { ...prev, minutes: prev.minutes - 1, seconds: 59 };
        } else if (prev.hours > 0) {
          return { hours: prev.hours - 1, minutes: 59, seconds: 59 };
        } else {
          // Maintenance period ended, try to reload
          window.location.reload();
          return prev;
        }
      });
    }, 1000);

    return () => clearInterval(timer);
  }, []);

  const formatTime = (time) => {
    return time.toString().padStart(2, '0');
  };

  return (
    <div className="maintenance-page">
      <div className="maintenance-container">
        <div className="maintenance-icon">
          <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#f39c12" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
            <path d="M12 8V12" stroke="#f39c12" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
            <path d="M12 16H12.01" stroke="#f39c12" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
          </svg>
        </div>
        <h1>System Maintenance</h1>
        <p>We're currently performing scheduled maintenance to improve our services.</p>
        
        <div className="countdown">
          <h2>Estimated Time Remaining</h2>
          <div className="countdown-timer">
            <div className="time-unit">
              <span className="time-value">{formatTime(timeLeft.hours)}</span>
              <span className="time-label">Hours</span>
            </div>
            <div className="time-separator">:</div>
            <div className="time-unit">
              <span className="time-value">{formatTime(timeLeft.minutes)}</span>
              <span className="time-label">Minutes</span>
            </div>
            <div className="time-separator">:</div>
            <div className="time-unit">
              <span className="time-value">{formatTime(timeLeft.seconds)}</span>
              <span className="time-label">Seconds</span>
            </div>
          </div>
        </div>
        
        <div className="maintenance-info">
          <h3>What you can do:</h3>
          <ul>
            <li>Check back in a few minutes</li>
            <li>Follow our social media for updates</li>
            <li>Contact support if you need immediate assistance</li>
          </ul>
        </div>
      </div>
    </div>
  );
};

export default MaintenancePage;