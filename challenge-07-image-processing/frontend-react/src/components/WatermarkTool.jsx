import React, { useState } from 'react';

const WatermarkTool = ({ onWatermarkApply }) => {
  const [text, setText] = useState('');
  const [position, setPosition] = useState('bottom-right');
  const [opacity, setOpacity] = useState(50);
  const [fontSize, setFontSize] = useState(20);

  const handleApplyWatermark = () => {
    if (!text.trim()) {
      alert('Please enter watermark text');
      return;
    }

    onWatermarkApply({
      text,
      position,
      opacity,
      fontSize
    });
  };

  return (
    <div className="watermark-tool">
      <h3>Watermark</h3>
      <div className="watermark-controls">
        <div className="control-group">
          <label>Text:</label>
          <input 
            type="text" 
            value={text} 
            onChange={(e) => setText(e.target.value)} 
            placeholder="Enter watermark text"
          />
        </div>
        
        <div className="control-group">
          <label>Position:</label>
          <select 
            value={position} 
            onChange={(e) => setPosition(e.target.value)}
          >
            <option value="top-left">Top Left</option>
            <option value="top-right">Top Right</option>
            <option value="bottom-left">Bottom Left</option>
            <option value="bottom-right">Bottom Right</option>
            <option value="center">Center</option>
          </select>
        </div>
        
        <div className="control-group">
          <label>Opacity: {opacity}%</label>
          <input 
            type="range" 
            min="0" 
            max="100" 
            value={opacity} 
            onChange={(e) => setOpacity(parseInt(e.target.value))} 
          />
        </div>
        
        <div className="control-group">
          <label>Font Size: {fontSize}px</label>
          <input 
            type="range" 
            min="10" 
            max="100" 
            value={fontSize} 
            onChange={(e) => setFontSize(parseInt(e.target.value))} 
          />
        </div>
        
        <button onClick={handleApplyWatermark}>Apply Watermark</button>
      </div>
    </div>
  );
};

export default WatermarkTool;