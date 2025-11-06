import React from 'react';

function ScanResults({ scanResult }) {
  if (!scanResult) {
    return null;
  }

  return (
    <div className="scan-results">
      <h4>Scan Results</h4>
      
      <div className={`scan-status ${scanResult.is_safe ? 'safe' : 'unsafe'}`}>
        <p><strong>Status:</strong> {scanResult.is_safe ? 'Safe' : 'Threats Detected'}</p>
      </div>
      
      <div className="scan-details">
        <p><strong>Scan Date:</strong> {new Date(scanResult.scan_date).toLocaleString()}</p>
        <p><strong>Scan Tool:</strong> {scanResult.scan_tool}</p>
        
        {scanResult.threats_found && scanResult.threats_found.length > 0 && (
          <div className="threats-found">
            <p><strong>Threats Found:</strong></p>
            <ul>
              {scanResult.threats_found.map((threat, index) => (
                <li key={index}>{threat}</li>
              ))}
            </ul>
          </div>
        )}
      </div>
    </div>
  );
}

export default ScanResults;