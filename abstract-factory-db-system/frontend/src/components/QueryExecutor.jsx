import React, { useState } from 'react';
import useConnectionStore from '../store/connectionStore';

const QueryExecutor = () => {
  const [sqlQuery, setSqlQuery] = useState('');
  const [queryParams, setQueryParams] = useState('');
  
  const activeConnection = useConnectionStore(state => state.activeConnection);
  const connections = useConnectionStore(state => state.connections);
  const isQueryLoading = useConnectionStore(state => state.isQueryLoading);
  const queryError = useConnectionStore(state => state.queryError);
  const queryResults = useConnectionStore(state => state.queryResults);
  const setQueryLoading = useConnectionStore(state => state.setQueryLoading);
  const setQueryError = useConnectionStore(state => state.setQueryError);
  const addQueryResult = useConnectionStore(state => state.addQueryResult);
  
  const connection = activeConnection ? connections[activeConnection] : null;
  
  const handleExecuteQuery = async () => {
    if (!connection) {
      setQueryError('No active database connection');
      return;
    }
    
    if (!sqlQuery.trim()) {
      setQueryError('Please enter a SQL query');
      return;
    }
    
    setQueryLoading(true);
    setQueryError(null);
    
    try {
      // Parse parameters if provided
      let params = [];
      if (queryParams.trim()) {
        try {
          params = JSON.parse(queryParams);
        } catch (e) {
          throw new Error('Invalid JSON in parameters');
        }
      }
      
      // In a real implementation, this would call the API to execute the query
      console.log('Executing query:', { 
        dbType: connection.type, 
        dbConfig: connection.config, 
        sql: sqlQuery, 
        params 
      });
      
      // Simulate API response
      const mockResult = {
        id: Date.now(),
        query: sqlQuery,
        timestamp: new Date().toISOString(),
        success: true,
        data: [
          { id: 1, name: 'John Doe', email: 'john@example.com' },
          { id: 2, name: 'Jane Smith', email: 'jane@example.com' }
        ]
      };
      
      addQueryResult(mockResult);
      setSqlQuery('');
      setQueryParams('');
    } catch (error) {
      setQueryError(error.message);
    } finally {
      setQueryLoading(false);
    }
  };
  
  return (
    <div className="bg-white rounded-lg shadow-md p-6 mb-6">
      <h2 className="text-xl font-bold mb-4">Query Executor</h2>
      
      {connection ? (
        <div className="mb-4 p-3 bg-blue-50 rounded-md">
          <p className="text-sm text-blue-800">
            Connected to {connection.type} database: {connection.config.database || 'unnamed'}
          </p>
        </div>
      ) : (
        <div className="mb-4 p-3 bg-yellow-50 rounded-md">
          <p className="text-sm text-yellow-800">
            No active database connection. Please connect to a database first.
          </p>
        </div>
      )}
      
      <div className="mb-4">
        <label className="block text-sm font-medium text-gray-700 mb-1">SQL Query</label>
        <textarea
          value={sqlQuery}
          onChange={(e) => setSqlQuery(e.target.value)}
          rows={4}
          className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="SELECT * FROM users WHERE id = ?"
        />
      </div>
      
      <div className="mb-4">
        <label className="block text-sm font-medium text-gray-700 mb-1">
          Parameters (JSON array)
        </label>
        <input
          type="text"
          value={queryParams}
          onChange={(e) => setQueryParams(e.target.value)}
          className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder='[1] or ["john"]'
        />
      </div>
      
      {queryError && (
        <div className="mb-4 p-3 bg-red-50 rounded-md">
          <p className="text-sm text-red-800">{queryError}</p>
        </div>
      )}
      
      <div className="flex space-x-3 mb-6">
        <button
          onClick={handleExecuteQuery}
          disabled={isQueryLoading || !connection}
          className={`px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 ${
            isQueryLoading || !connection
              ? 'bg-gray-400 cursor-not-allowed'
              : 'bg-green-600 text-white hover:bg-green-700'
          }`}
        >
          {isQueryLoading ? 'Executing...' : 'Execute Query'}
        </button>
        
        <button
          onClick={() => {
            setSqlQuery('');
            setQueryParams('');
            setQueryError(null);
          }}
          className="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
        >
          Clear
        </button>
      </div>
      
      {queryResults.length > 0 && (
        <div>
          <h3 className="text-lg font-medium mb-2">Query Results</h3>
          {queryResults.map((result) => (
            <div key={result.id} className="border rounded-md mb-4">
              <div className="bg-gray-50 px-4 py-2 border-b">
                <p className="text-sm font-medium">
                  Query: {result.query}
                </p>
                <p className="text-xs text-gray-500">
                  Executed at: {new Date(result.timestamp).toLocaleString()}
                </p>
              </div>
              <div className="p-4">
                {result.success ? (
                  <div className="overflow-x-auto">
                    <table className="min-w-full divide-y divide-gray-200">
                      <thead className="bg-gray-50">
                        <tr>
                          {result.data[0] && Object.keys(result.data[0]).map((key) => (
                            <th
                              key={key}
                              className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                              {key}
                            </th>
                          ))}
                        </tr>
                      </thead>
                      <tbody className="bg-white divide-y divide-gray-200">
                        {result.data.map((row, index) => (
                          <tr key={index}>
                            {Object.values(row).map((value, i) => (
                              <td key={i} className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {value}
                              </td>
                            ))}
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  </div>
                ) : (
                  <div className="p-4 bg-red-50 rounded-md">
                    <p className="text-sm text-red-800">
                      Query failed: {result.error}
                    </p>
                  </div>
                )}
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default QueryExecutor;