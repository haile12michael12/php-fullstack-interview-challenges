import React, { createContext, useContext, useState, useEffect } from 'react';
import { memoryService } from '../services/memoryService';

const MemoryContext = createContext();

export function useMemory() {
  return useContext(MemoryContext);
}

export function MemoryProvider({ children }) {
  const [memoryData, setMemoryData] = useState({
    currentUsage: 0,
    peakUsage: 0,
    formattedUsage: '0 MB',
    formattedPeak: '0 MB',
  });
  
  const [alerts, setAlerts] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  
  // Fetch memory data periodically
  useEffect(() => {
    const interval = setInterval(() => {
      fetchMemoryData();
    }, 5000); // Update every 5 seconds
    
    // Initial fetch
    fetchMemoryData();
    
    return () => clearInterval(interval);
  }, []);
  
  const fetchMemoryData = async () => {
    try {
      setLoading(true);
      const data = await memoryService.getProfile();
      setMemoryData(data.data.current);
      
      // Check for alerts
      const alertData = await memoryService.getLeaks();
      setAlerts(alertData.data.potential_leaks || []);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };
  
  const optimizeMemory = async () => {
    try {
      setLoading(true);
      const result = await memoryService.optimize();
      return result;
    } catch (err) {
      setError(err.message);
      throw err;
    } finally {
      setLoading(false);
    }
  };
  
  const analyzeMemory = async () => {
    try {
      setLoading(true);
      const result = await memoryService.analyze();
      return result;
    } catch (err) {
      setError(err.message);
      throw err;
    } finally {
      setLoading(false);
    }
  };
  
  const value = {
    memoryData,
    alerts,
    loading,
    error,
    fetchMemoryData,
    optimizeMemory,
    analyzeMemory,
  };
  
  return (
    <MemoryContext.Provider value={value}>
      {children}
    </MemoryContext.Provider>
  );
}