import React, { useState } from 'react';

const DebugInfoCard = ({ title, data, onClose }) => {
  const [expanded, setExpanded] = useState(false);

  const toggleExpanded = () => {
    setExpanded(!expanded);
  };

  const formatData = (data) => {
    if (typeof data === 'object' && data !== null) {
      return JSON.stringify(data, null, 2);
    }
    return String(data);
  };

  return (
    <div className="debug-info-card">
      <div className="card-header">
        <h4>{title}</h4>
        <div className="card-actions">
          <button onClick={toggleExpanded}>
            {expanded ? 'Collapse' : 'Expand'}
          </button>
          {onClose && <button onClick={onClose}>Close</button>}
        </div>
      </div>
      
      <div className={`card-content ${expanded ? 'expanded' : ''}`}>
        <pre>{formatData(data)}</pre>
      </div>
    </div>
  );
};

export default DebugInfoCard;