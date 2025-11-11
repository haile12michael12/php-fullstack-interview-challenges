import React, { useState, useEffect } from 'react';
import { generateFibonacci, generatePrimes } from '../services/generatorService';

const PerformanceChart = () => {
  const [fibonacciData, setFibonacciData] = useState([]);
  const [primeData, setPrimeData] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchData = async () => {
      setLoading(true);
      setError(null);
      
      try {
        const [fibResult, primeResult] = await Promise.all([
          generateFibonacci(20),
          generatePrimes(50)
        ]);
        
        setFibonacciData(fibResult);
        setPrimeData(primeResult);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  if (loading) return <div>Loading performance data...</div>;
  if (error) return <div>Error: {error}</div>;

  return (
    <div className="performance-chart">
      <h2>Performance Chart</h2>
      
      <div className="chart-section">
        <h3>Fibonacci Sequence</h3>
        <div className="sequence-display">
          {fibonacciData.map((num, index) => (
            <div key={index} className="sequence-item">
              <span className="index">{index}:</span>
              <span className="value">{num}</span>
            </div>
          ))}
        </div>
      </div>
      
      <div className="chart-section">
        <h3>Prime Numbers</h3>
        <div className="sequence-display">
          {primeData.map((num, index) => (
            <div key={index} className="sequence-item">
              <span className="value">{num}</span>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default PerformanceChart;