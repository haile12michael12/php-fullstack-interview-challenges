import React, { useEffect, useRef } from 'react';

function PerformanceChart({ data = [] }) {
  const canvasRef = useRef(null);
  
  useEffect(() => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const { width, height } = canvas;
    
    // Clear canvas
    ctx.clearRect(0, 0, width, height);
    
    // Draw grid
    ctx.strokeStyle = '#e5e7eb';
    ctx.lineWidth = 1;
    
    // Vertical grid lines
    for (let i = 0; i <= 10; i++) {
      const x = (width / 10) * i;
      ctx.beginPath();
      ctx.moveTo(x, 0);
      ctx.lineTo(x, height);
      ctx.stroke();
    }
    
    // Horizontal grid lines
    for (let i = 0; i <= 5; i++) {
      const y = (height / 5) * i;
      ctx.beginPath();
      ctx.moveTo(0, y);
      ctx.lineTo(width, y);
      ctx.stroke();
    }
    
    // Draw sample data if no data provided
    if (data.length === 0) {
      drawSampleData(ctx, width, height);
    } else {
      drawChartData(ctx, width, height, data);
    }
  }, [data]);
  
  const drawSampleData = (ctx, width, height) => {
    ctx.strokeStyle = '#4f46e5';
    ctx.lineWidth = 2;
    ctx.beginPath();
    
    // Generate sample data points
    const points = [];
    for (let i = 0; i < 10; i++) {
      const x = (width / 9) * i;
      const y = height - (Math.sin(i / 2) * height / 3 + height / 2);
      points.push({ x, y });
    }
    
    // Draw line
    ctx.moveTo(points[0].x, points[0].y);
    for (let i = 1; i < points.length; i++) {
      ctx.lineTo(points[i].x, points[i].y);
    }
    ctx.stroke();
    
    // Draw points
    ctx.fillStyle = '#4f46e5';
    points.forEach(point => {
      ctx.beginPath();
      ctx.arc(point.x, point.y, 4, 0, Math.PI * 2);
      ctx.fill();
    });
  };
  
  const drawChartData = (ctx, width, height, data) => {
    if (data.length === 0) return;
    
    ctx.strokeStyle = '#4f46e5';
    ctx.lineWidth = 2;
    ctx.beginPath();
    
    // Find min and max values for scaling
    const memoryValues = data.map(item => item.memory);
    const minMemory = Math.min(...memoryValues);
    const maxMemory = Math.max(...memoryValues);
    const range = maxMemory - minMemory || 1; // Avoid division by zero
    
    // Generate points
    const points = [];
    for (let i = 0; i < data.length; i++) {
      const x = (width / (data.length - 1)) * i;
      const normalizedValue = (data[i].memory - minMemory) / range;
      const y = height - (normalizedValue * height * 0.8 + height * 0.1); // Leave 10% margin
      points.push({ x, y });
    }
    
    // Draw line
    ctx.moveTo(points[0].x, points[0].y);
    for (let i = 1; i < points.length; i++) {
      ctx.lineTo(points[i].x, points[i].y);
    }
    ctx.stroke();
    
    // Draw points
    ctx.fillStyle = '#4f46e5';
    points.forEach(point => {
      ctx.beginPath();
      ctx.arc(point.x, point.y, 4, 0, Math.PI * 2);
      ctx.fill();
    });
  };
  
  return (
    <canvas
      ref={canvasRef}
      width="100%"
      height="100%"
      style={{ width: '100%', height: '100%' }}
    />
  );
}

export default PerformanceChart;