import React from 'react';
import QueryBuilder from '../components/QueryBuilder';

const QueryPage = () => {
  return (
    <div className="query-page">
      <h1>Query Builder</h1>
      <p>Build and execute dynamic queries using magic methods.</p>
      
      <QueryBuilder />
    </div>
  );
};

export default QueryPage;