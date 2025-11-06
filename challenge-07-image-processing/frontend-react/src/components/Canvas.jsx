import React, { useEffect, useRef, forwardRef } from 'react';

const Canvas = forwardRef(({ image, filters, dimensions }, ref) => {
  const canvasRef = ref || useRef(null);

  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas || !image) return;

    const ctx = canvas.getContext('2d');
    canvas.width = dimensions.width;
    canvas.height = dimensions.height;

    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Draw image
    ctx.drawImage(image, 0, 0, canvas.width, canvas.height);

    // Apply filters
    if (filters.brightness) {
      ctx.filter = `brightness(${filters.brightness}%)`;
    }
    if (filters.contrast) {
      ctx.filter += ` contrast(${filters.contrast}%)`;
    }
    if (filters.saturation) {
      ctx.filter += ` saturate(${filters.saturation}%)`;
    }
  }, [image, filters, dimensions]);

  return (
    <canvas 
      ref={canvasRef} 
      width={dimensions.width} 
      height={dimensions.height}
      style={{ border: '1px solid #ccc' }}
    />
  );
});

export default Canvas;