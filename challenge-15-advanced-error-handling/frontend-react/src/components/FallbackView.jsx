import React from 'react';

const FallbackView = ({ message = "Loading content...", isLoading = true }) => {
  if (!isLoading) return null;

  return (
    <div className="fallback-view">
      <div className="fallback-content">
        <div className="spinner"></div>
        <p>{message}</p>
      </div>
    </div>
  );
};

export default FallbackView;