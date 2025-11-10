import React, { useState } from 'react';
import { useMemory } from '../context/MemoryContext';

function MemoryOptimizer() {
  const { optimizeMemory } = useMemory();
  const [isOptimizing, setIsOptimizing] = useState(false);
  const [optimizationResults, setOptimizationResults] = useState(null);
  
  const runOptimization = async () => {
    setIsOptimizing(true);
    try {
      const results = await optimizeMemory();
      setOptimizationResults(results.data);
    } catch (error) {
      console.error('Optimization failed:', error);
    } finally {
      setIsOptimizing(false);
    }
  };
  
  return (
    <div className="card">
      <div className="card-header">
        <h2>Memory Optimizer</h2>
        <button 
          className={`btn ${isOptimizing ? 'btn-secondary' : 'btn-primary'}`}
          onClick={runOptimization}
          disabled={isOptimizing}
        >
          {isOptimizing ? 'Optimizing...' : 'Run Optimization'}
        </button>
      </div>
      
      {isOptimizing && (
        <div className="optimization-progress">
          <p>Running memory optimization techniques...</p>
          <ul>
            <li>Implementing object pooling</li>
            <li>Applying lazy loading patterns</li>
            <li>Optimizing data structures</li>
            <li>Clearing unused resources</li>
          </ul>
        </div>
      )}
      
      {optimizationResults && (
        <div className="optimization-results">
          <div className="results-summary">
            <h3>Optimization Complete</h3>
            <div className="stat-item">
              <span className="label">Memory Saved:</span>
              <span className="value">{optimizationResults.memory_comparison.formatted.difference}</span>
            </div>
            <div className="stat-item">
              <span className="label">Improvement:</span>
              <span className="value">{optimizationResults.memory_comparison.percentage_change}%</span>
            </div>
          </div>
          
          <div className="techniques-applied">
            <h3>Techniques Applied</h3>
            <ul>
              {optimizationResults.recommendations.map((recommendation, index) => (
                <li key={index}>
                  <strong>{recommendation.type}:</strong> {recommendation.recommendation}
                </li>
              ))}
            </ul>
          </div>
        </div>
      )}
      
      {!optimizationResults && !isOptimizing && (
        <div className="no-optimization">
          <p>Run memory optimization to apply techniques that reduce memory consumption.</p>
          <ul>
            <li>Object pooling for frequently created objects</li>
            <li>Lazy loading for heavy resources</li>
            <li>Data structure optimization</li>
            <li>Resource cleanup and garbage collection</li>
          </ul>
        </div>
      )}
    </div>
  );
}

export default MemoryOptimizer;