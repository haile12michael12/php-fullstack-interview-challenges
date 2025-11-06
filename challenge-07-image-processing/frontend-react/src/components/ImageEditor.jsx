import React, { useState, useRef } from 'react';
import Canvas from './Canvas.jsx';
import Toolbar from './Toolbar.jsx';
import PreviewPanel from './PreviewPanel.jsx';

const ImageEditor = () => {
  const [image, setImage] = useState(null);
  const [filters, setFilters] = useState({});
  const [dimensions, setDimensions] = useState({ width: 0, height: 0 });
  const canvasRef = useRef(null);

  const handleImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        const img = new Image();
        img.onload = () => {
          setImage(img);
          setDimensions({ width: img.width, height: img.height });
        };
        img.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  };

  const applyFilter = (filterType, value) => {
    setFilters(prev => ({
      ...prev,
      [filterType]: value
    }));
  };

  return (
    <div className="image-editor">
      <h2>Image Editor</h2>
      <input 
        type="file" 
        accept="image/*" 
        onChange={handleImageUpload} 
      />
      
      {image && (
        <>
          <Toolbar onFilterApply={applyFilter} />
          <div className="editor-container">
            <Canvas 
              image={image} 
              filters={filters} 
              dimensions={dimensions}
              ref={canvasRef}
            />
            <PreviewPanel 
              image={image} 
              filters={filters} 
              dimensions={dimensions}
            />
          </div>
        </>
      )}
    </div>
  );
};

export default ImageEditor;