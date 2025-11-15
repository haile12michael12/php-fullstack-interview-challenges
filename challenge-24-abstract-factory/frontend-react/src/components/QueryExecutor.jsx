import React, { useState } from 'react';

const QueryExecutor = () => {
  const [query, setQuery] = useState('');
  const [results, setResults] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const executeQuery = async () => {
    if (!query.trim()) return;
    
    setLoading(true);
    setError(null);
    setResults(null);
    
    try {
      // In a real implementation, this would execute the query
      // For this demo, we'll just simulate a response
      const mockResults = {
        status: 'success',
        data: [
          { id: 1, name: 'John Doe', email: 'john@example.com' },
          { id: 2, name: 'Jane Smith', email: 'jane@example.com' }
        ],
        query: query,
        executionTime: '0.005s'
      };
      
      setResults(mockResults);
    } catch (err) {
      setError('Failed to execute query');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="query-executor">
      <h3>Query Executor</h3>
      <div className="query-input">
        <textarea
          value={query}
          onChange={(e) => setQuery(e.target.value)}
          placeholder="Enter your SQL query here..."
          rows={6}
          disabled={loading}
        />
        <button onClick={executeQuery} disabled={!query.trim() || loading}>
          Execute Query
        </button>
      </div>
      
      {loading && <div className="loading">Executing query...</div>}
      
      {error && <div className="error">{error}</div>}
      
      {results && (
        <div className="query-results">
          <h4>Results</h4>
          <div className="execution-info">
            Execution time: {results.executionTime}
          </div>
          <pre>{JSON.stringify(results.data, null, 2)}</pre>
        </div>
      )}
    </div>
  );
};

export default QueryExecutor;