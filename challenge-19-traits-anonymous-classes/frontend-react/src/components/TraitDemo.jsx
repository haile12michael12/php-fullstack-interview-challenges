import React, { useState } from 'react';
import { oopService } from '../services/oopService';

const TraitDemo = () => {
  const [logMessage, setLogMessage] = useState('');
  const [logLevel, setLogLevel] = useState('info');
  const [calcNumber, setCalcNumber] = useState(5);
  const [calcResult, setCalcResult] = useState(null);
  const [validateData, setValidateData] = useState({ email: '', age: '' });
  const [validationResult, setValidationResult] = useState(null);
  const [cacheKey, setCacheKey] = useState('demo');
  const [cacheValue, setCacheValue] = useState('');
  const [cachedData, setCachedData] = useState(null);

  const handleLogMessage = async () => {
    try {
      const result = await oopService.logMessage(logMessage, logLevel);
      console.log('Message logged:', result);
    } catch (error) {
      console.error('Error logging message:', error);
    }
  };

  const handleCalculate = async () => {
    try {
      const result = await oopService.performCalculation(calcNumber);
      setCalcResult(result);
    } catch (error) {
      console.error('Error performing calculation:', error);
    }
  };

  const handleValidate = async () => {
    try {
      const result = await oopService.validateEntity(validateData);
      setValidationResult(result);
    } catch (error) {
      console.error('Error validating data:', error);
    }
  };

  const handleCacheData = async () => {
    try {
      const result = await oopService.cacheData(cacheKey, cacheValue);
      console.log('Data cached:', result);
    } catch (error) {
      console.error('Error caching data:', error);
    }
  };

  const handleGetCachedData = async () => {
    try {
      const result = await oopService.getCachedData(cacheKey);
      setCachedData(result);
    } catch (error) {
      console.error('Error getting cached data:', error);
    }
  };

  return (
    <div className="trait-demo">
      <h2>Traits Demo</h2>
      
      <div className="demo-section">
        <h3>Logger Trait</h3>
        <div>
          <input 
            type="text" 
            value={logMessage} 
            onChange={(e) => setLogMessage(e.target.value)} 
            placeholder="Enter message to log"
          />
          <select value={logLevel} onChange={(e) => setLogLevel(e.target.value)}>
            <option value="info">Info</option>
            <option value="error">Error</option>
            <option value="debug">Debug</option>
          </select>
          <button onClick={handleLogMessage}>Log Message</button>
        </div>
      </div>

      <div className="demo-section">
        <h3>Cacheable Trait</h3>
        <div>
          <input 
            type="number" 
            value={calcNumber} 
            onChange={(e) => setCalcNumber(e.target.value)} 
            placeholder="Enter number"
          />
          <button onClick={handleCalculate}>Calculate Cube</button>
          {calcResult && (
            <div>
              <p>Result: {calcResult.result}</p>
              <p>Time: {calcResult.calculation_time.toFixed(4)}s</p>
            </div>
          )}
        </div>
      </div>

      <div className="demo-section">
        <h3>Validatable Trait</h3>
        <div>
          <input 
            type="email" 
            value={validateData.email} 
            onChange={(e) => setValidateData({...validateData, email: e.target.value})} 
            placeholder="Enter email"
          />
          <input 
            type="number" 
            value={validateData.age} 
            onChange={(e) => setValidateData({...validateData, age: e.target.value})} 
            placeholder="Enter age"
          />
          <button onClick={handleValidate}>Validate</button>
          {validationResult && (
            <div>
              {validationResult.status === 'success' ? (
                <p>Validation successful!</p>
              ) : (
                <div>
                  <p>Validation errors:</p>
                  <ul>
                    {Object.entries(validationResult.errors).map(([field, errors]) => (
                      <li key={field}>{field}: {errors.join(', ')}</li>
                    ))}
                  </ul>
                </div>
              )}
            </div>
          )}
        </div>
      </div>

      <div className="demo-section">
        <h3>Cache Operations</h3>
        <div>
          <input 
            type="text" 
            value={cacheKey} 
            onChange={(e) => setCacheKey(e.target.value)} 
            placeholder="Cache key"
          />
          <input 
            type="text" 
            value={cacheValue} 
            onChange={(e) => setCacheValue(e.target.value)} 
            placeholder="Cache value"
          />
          <button onClick={handleCacheData}>Cache Data</button>
          <button onClick={handleGetCachedData}>Get Cached Data</button>
          {cachedData && (
            <div>
              <p>Key: {cachedData.key}</p>
              <p>Value: {cachedData.value}</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default TraitDemo;