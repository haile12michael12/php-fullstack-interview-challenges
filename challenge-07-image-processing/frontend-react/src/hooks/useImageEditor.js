import { useState, useRef } from 'react';

const useImageEditor = () => {
  const [image, setImage] = useState(null);
  const [filters, setFilters] = useState({});
  const [dimensions, setDimensions] = useState({ width: 0, height: 0 });
  const canvasRef = useRef(null);

  const handleImageUpload = (file) => {
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

  const resetFilters = () => {
    setFilters({});
  };

  return {
    image,
    filters,
    dimensions,
    canvasRef,
    handleImageUpload,
    applyFilter,
    resetFilters
  };
};

export default useImageEditor;