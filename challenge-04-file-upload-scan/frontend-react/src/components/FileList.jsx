import React from 'react';
import FilePreview from './FilePreview';

function FileList({ files }) {
  if (!files || files.length === 0) {
    return <p className="no-files">No files uploaded yet.</p>;
  }

  const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  };

  const formatTimestamp = (timestamp) => {
    return new Date(timestamp * 1000).toLocaleString();
  };

  return (
    <div className="file-list">
      {files.map((file) => (
        <div key={file.id} className="file-item">
          <div className="file-info">
            <h3>{file.original_name}</h3>
            <p><strong>Size:</strong> {formatFileSize(file.file_size)}</p>
            <p><strong>Uploaded:</strong> {formatTimestamp(file.upload_time)}</p>
            <p><strong>Scan Status:</strong> 
              <span className={file.scan_result?.is_safe ? 'status-safe' : 'status-unsafe'}>
                {file.scan_result?.is_safe ? 'Safe' : 'Threats Detected'}
              </span>
            </p>
          </div>
          
          <div className="file-actions">
            <button onClick={() => console.log('Download', file.id)}>Download</button>
            <button onClick={() => console.log('Delete', file.id)} className="delete-btn">Delete</button>
          </div>
        </div>
      ))}
    </div>
  );
}

export default FileList;