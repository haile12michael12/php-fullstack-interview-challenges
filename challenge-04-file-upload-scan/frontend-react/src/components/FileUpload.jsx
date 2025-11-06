import React, { useState } from 'react';

function FileUpload({ onUpload }) {
  const [selectedFile, setSelectedFile] = useState(null);
  const [uploading, setUploading] = useState(false);
  const [uploadResult, setUploadResult] = useState(null);
  const [error, setError] = useState('');

  const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      setSelectedFile(file);
      setError('');
      setUploadResult(null);
    }
  };

  const handleUpload = async () => {
    if (!selectedFile) {
      setError('Please select a file to upload');
      return;
    }

    setUploading(true);
    setError('');
    
    try {
      const result = await onUpload(selectedFile);
      setUploadResult(result);
    } catch (err) {
      setError(err.message);
    } finally {
      setUploading(false);
    }
  };

  const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  };

  return (
    <div className="file-upload">
      <h2>Upload File</h2>
      
      <div className="upload-form">
        <input 
          type="file" 
          onChange={handleFileChange}
          disabled={uploading}
        />
        
        {selectedFile && (
          <div className="file-info">
            <p><strong>File:</strong> {selectedFile.name}</p>
            <p><strong>Size:</strong> {formatFileSize(selectedFile.size)}</p>
            <p><strong>Type:</strong> {selectedFile.type}</p>
          </div>
        )}
        
        <button 
          onClick={handleUpload} 
          disabled={!selectedFile || uploading}
        >
          {uploading ? 'Uploading...' : 'Upload File'}
        </button>
      </div>
      
      {error && (
        <div className="error-message">
          <p>Error: {error}</p>
        </div>
      )}
      
      {uploadResult && (
        <div className="success-message">
          <p>File uploaded successfully!</p>
          {uploadResult.file && (
            <div className="file-details">
              <p><strong>Original Name:</strong> {uploadResult.file.original_name}</p>
              <p><strong>Scan Result:</strong> {uploadResult.file.scan_result?.is_safe ? 'Safe' : 'Threats Detected'}</p>
            </div>
          )}
        </div>
      )}
    </div>
  );
}

export default FileUpload;