import { useState, useEffect } from 'react';
import logService from '../services/logService';

const useFetchLogs = (filters = {}) => {
  const [logs, setLogs] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchLogs();
  }, [filters]);

  const fetchLogs = async () => {
    setLoading(true);
    setError(null);
    
    try {
      const data = await logService.getAllLogs(filters);
      setLogs(data);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const refetch = () => {
    fetchLogs();
  };

  return { logs, loading, error, refetch };
};

export default useFetchLogs;