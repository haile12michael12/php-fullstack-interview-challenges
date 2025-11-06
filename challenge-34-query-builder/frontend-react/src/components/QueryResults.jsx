import React from 'react';
import './QueryResults.css';

const QueryResults = ({ results, query, loading, error }) => {
  if (loading) {
    return <div className="query-results loading">Executing query...</div>;
  }

  if (error) {
    return (
      <div className="query-results error">
        <h3>Error:</h3>
        <pre>{error}</pre>
      </div>
    );
  }

  if (!results) {
    return <div className="query-results empty">No query executed yet</div>;
  }

  return (
    <div className="query-results">
      <div className="query-info">
        <h3>Executed Query:</h3>
        <pre>{query}</pre>
      </div>

      <div className="results-info">
        <h3>Results:</h3>
        {Array.isArray(results) && results.length > 0 ? (
          <div className="table-container">
            <table>
              <thead>
                <tr>
                  {Object.keys(results[0]).map((key) => (
                    <th key={key}>{key}</th>
                  ))}
                </tr>
              </thead>
              <tbody>
                {results.map((row, index) => (
                  <tr key={index}>
                    {Object.values(row).map((value, i) => (
                      <td key={i}>{value !== null ? value.toString() : 'NULL'}</td>
                    ))}
                  </tr>
                ))}
              </tbody>
            </table>
            <p className="result-count">{results.length} row(s) returned</p>
          </div>
        ) : (
          <p className="no-results">No results found</p>
        )}
      </div>
    </div>
  );
};

export default QueryResults;