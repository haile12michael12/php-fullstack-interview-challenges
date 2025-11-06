import React from 'react';

const PreviewPanel = ({ image, filters, dimensions }) => {
  const applyFilters = () => {
    let filterString = '';
    if (filters.brightness) {
      filterString += `brightness(${filters.brightness}%) `;
    }
    if (filters.contrast) {
      filterString += `contrast(${filters.contrast}%) `;
    }
    if (filters.saturation) {
      filterString += `saturate(${filters.saturation}%) `;
    }
    return filterString.trim();
  };

  return (
    <div className="preview-panel">
      <h3>Preview</h3>
      {image && (
        <img 
          src={image.src} 
          alt="Preview" 
          style={{ 
            maxWidth: '300px', 
            maxHeight: '300px',
            filter: applyFilters()
          }} 
        />
      )}
      <div className="dimensions">
        <p>Dimensions: {dimensions.width} x {dimensions.height}</p>
      </div>
    </div>
  );
};

export default PreviewPanel;