import React, { useState } from 'react';

const Toolbar = ({ onFilterApply }) => {
  const [brightness, setBrightness] = useState(100);
  const [contrast, setContrast] = useState(100);
  const [saturation, setSaturation] = useState(100);

  const handleBrightnessChange = (e) => {
    const value = parseInt(e.target.value);
    setBrightness(value);
    onFilterApply('brightness', value);
  };

  const handleContrastChange = (e) => {
    const value = parseInt(e.target.value);
    setContrast(value);
    onFilterApply('contrast', value);
  };

  const handleSaturationChange = (e) => {
    const value = parseInt(e.target.value);
    setSaturation(value);
    onFilterApply('saturation', value);
  };

  return (
    <div className="toolbar">
      <h3>Filters</h3>
      <div className="filter-control">
        <label>Brightness: {brightness}%</label>
        <input 
          type="range" 
          min="0" 
          max="200" 
          value={brightness} 
          onChange={handleBrightnessChange} 
        />
      </div>
      
      <div className="filter-control">
        <label>Contrast: {contrast}%</label>
        <input 
          type="range" 
          min="0" 
          max="200" 
          value={contrast} 
          onChange={handleContrastChange} 
        />
      </div>
      
      <div className="filter-control">
        <label>Saturation: {saturation}%</label>
        <input 
          type="range" 
          min="0" 
          max="200" 
          value={saturation} 
          onChange={handleSaturationChange} 
        />
      </div>
    </div>
  );
};

export default Toolbar;