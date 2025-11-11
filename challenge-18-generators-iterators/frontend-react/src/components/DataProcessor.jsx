import React, { useState, useEffect } from 'react';
import { processData } from '../services/generatorService';

const DataProcessor = () => {
  const [csvData, setCsvData] = useState([]);
  const [fileData, setFileData] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchData = async () => {
      setLoading(true);
      setError(null);
      
      try {
        const [csvResult, fileResult] = await Promise.all([
          processData('csv'),
          processData('file')
        ]);
        
        setCsvData(csvResult);
        setFileData(fileResult);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  if (loading) return <div>Loading data processor...</div>;
  if (error) return <div>Error: {error}</div>;

  return (
    <div className="data-processor">
      <h2>Data Processor</h2>
      
      <div className="data-section">
        <h3>CSV Data Processing</h3>
        <div className="data-grid">
          {csvData.map((item, index) => (
            <div key={index} className="data-card">
              <h4>{item.name}</h4>
              <p>Age: {item.age}</p>
              <p>City: {item.city}</p>
            </div>
          ))}
        </div>
      </div>
      
      <div className="data-section">
        <h3>File Word Count</h3>
        <div className="word-count">
          {Object.entries(fileData).map(([word, count]) => (
            <div key={word} className="word-item">
              <span className="word">{word}</span>
              <span className="count">({count})</span>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default DataProcessor;