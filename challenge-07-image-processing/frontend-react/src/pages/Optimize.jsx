import React, { useState } from 'react';

const Optimize = () => {
  const [selectedFile, setSelectedFile] = useState(null);
  const [compressionLevel, setCompressionLevel] = useState(80);
  const [isOptimizing, setIsOptimizing] = useState(false);
  const [result, setResult] = useState(null);

  const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
      setSelectedFile(file);
      setResult(null);
    }
  };

  const handleOptimize = async () => {
    if (!selectedFile) {
      alert('Please select a file to optimize');
      return;
    }

    setIsOptimizing(true);
    try {
      // Simulate optimization process
      await new Promise(resolve => setTimeout(resolve, 2000));
      
      setResult({
        originalSize: (selectedFile.size / 1024).toFixed(2),
        optimizedSize: (selectedFile.size / 1024 * 0.7).toFixed(2), // Simulated compression
        savings: '30%'
      });
    } catch (error) {
      console.error('Optimization error:', error);
    } finally {
      setIsOptimizing(false);
    }
  };

  return (
    <div className="optimize-page">
      <header>
        <h1>Image Optimization</h1>
        <p>Reduce file size while maintaining quality</p>
      </header>
      
      <main>
        <div className="optimizer-container">
          <input 
            type="file" 
            accept="image/*" 
            onChange={handleFileChange} 
          />
          
          {selectedFile && (
            <div className="optimization-controls">
              <div className="control-group">
                <label>Compression Level: {compressionLevel}%</label>
                <input 
                  type="range" 
                  min="10" 
                  max="100" 
                  value={compressionLevel} 
                  onChange={(e) => setCompressionLevel(parseInt(e.target.value))} 
                />
              </div>
              
              <button 
                onClick={handleOptimize} 
                disabled={isOptimizing}
              >
                {isOptimizing ? 'Optimizing...' : 'Optimize Image'}
              </button>
            </div>
          )}
          
          {result && (
            <div className="optimization-result">
              <h3>Optimization Result</h3>
              <p>Original Size: {result.originalSize} KB</p>
              <p>Optimized Size: {result.optimizedSize} KB</p>
              <p>Savings: {result.savings}</p>
            </div>
          )}
        </div>
      </main>
    </div>
  );
};

export default Optimize;