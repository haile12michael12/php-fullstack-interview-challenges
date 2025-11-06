import React from 'react';

function FilePreview({ file }) {
  const isImage = (mimeType) => {
    return mimeType && mimeType.startsWith('image/');
  };

  const isText = (mimeType) => {
    return mimeType && (
      mimeType.startsWith('text/') || 
      mimeType === 'application/json' ||
      mimeType === 'application/xml'
    );
  };

  return (
    <div className="file-preview">
      {file && (
        <>
          <h4>File Preview: {file.original_name}</h4>
          
          {isImage(file.mime_type) && (
            <div className="image-preview">
              <img 
                src={`/api/files/${file.id}/preview`} 
                alt={file.original_name}
                onError={(e) => {
                  e.target.style.display = 'none';
                  e.target.nextSibling.style.display = 'block';
                }}
              />
              <p>Image preview not available</p>
            </div>
          )}
          
          {isText(file.mime_type) && (
            <div className="text-preview">
              <p>Text preview would be displayed here</p>
            </div>
          )}
          
          {!isImage(file.mime_type) && !isText(file.mime_type) && (
            <div className="generic-preview">
              <p>No preview available for this file type</p>
            </div>
          )}
        </>
      )}
    </div>
  );
}

export default FilePreview;