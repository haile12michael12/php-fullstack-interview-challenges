import React, { useState } from 'react';
import DebugInfoCard from './DebugInfoCard';

const QueryBuilder = () => {
  const [usersQuery, setUsersQuery] = useState({ name: '', email: '' });
  const [postsQuery, setPostsQuery] = useState({ title: '' });
  const [customQuery, setCustomQuery] = useState({ table: 'users', conditions: [] });
  const [usersResult, setUsersResult] = useState(null);
  const [postsResult, setPostsResult] = useState(null);
  const [customResult, setCustomResult] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const queryUsers = async () => {
    setLoading(true);
    setError(null);
    try {
      const params = new URLSearchParams();
      if (usersQuery.name) params.append('name', usersQuery.name);
      if (usersQuery.email) params.append('email', usersQuery.email);
      
      const response = await fetch(`/api/query/users?${params.toString()}`);
      const data = await response.json();
      setUsersResult(data);
    } catch (err) {
      setError('Failed to query users');
    } finally {
      setLoading(false);
    }
  };

  const queryPosts = async () => {
    setLoading(true);
    setError(null);
    try {
      const params = new URLSearchParams();
      if (postsQuery.title) params.append('title', postsQuery.title);
      
      const response = await fetch(`/api/query/posts?${params.toString()}`);
      const data = await response.json();
      setPostsResult(data);
    } catch (err) {
      setError('Failed to query posts');
    } finally {
      setLoading(false);
    }
  };

  const executeCustomQuery = async () => {
    setLoading(true);
    setError(null);
    try {
      const response = await fetch('/api/query/custom', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(customQuery),
      });
      
      const data = await response.json();
      setCustomResult(data);
    } catch (err) {
      setError('Failed to execute custom query');
    } finally {
      setLoading(false);
    }
  };

  const addCondition = () => {
    setCustomQuery({
      ...customQuery,
      conditions: [...customQuery.conditions, { column: '', operator: '=', value: '', boolean: 'and' }]
    });
  };

  const updateCondition = (index, field, value) => {
    const newConditions = [...customQuery.conditions];
    newConditions[index][field] = value;
    setCustomQuery({ ...customQuery, conditions: newConditions });
  };

  const removeCondition = (index) => {
    const newConditions = [...customQuery.conditions];
    newConditions.splice(index, 1);
    setCustomQuery({ ...customQuery, conditions: newConditions });
  };

  return (
    <div className="query-builder">
      <h2>Query Builder</h2>
      
      {error && <div className="error">{error}</div>}
      {loading && <div className="loading">Loading...</div>}
      
      <div className="query-section">
        <h3>Query Users</h3>
        <input
          type="text"
          placeholder="Name"
          value={usersQuery.name}
          onChange={(e) => setUsersQuery({...usersQuery, name: e.target.value})}
        />
        <input
          type="text"
          placeholder="Email"
          value={usersQuery.email}
          onChange={(e) => setUsersQuery({...usersQuery, email: e.target.value})}
        />
        <button onClick={queryUsers}>Query Users</button>
        {usersResult && (
          <DebugInfoCard 
            title="Users Query Results" 
            data={usersResult} 
          />
        )}
      </div>
      
      <div className="query-section">
        <h3>Query Posts</h3>
        <input
          type="text"
          placeholder="Title"
          value={postsQuery.title}
          onChange={(e) => setPostsQuery({...postsQuery, title: e.target.value})}
        />
        <button onClick={queryPosts}>Query Posts</button>
        {postsResult && (
          <DebugInfoCard 
            title="Posts Query Results" 
            data={postsResult} 
          />
        )}
      </div>
      
      <div className="query-section">
        <h3>Custom Query</h3>
        <input
          type="text"
          placeholder="Table"
          value={customQuery.table}
          onChange={(e) => setCustomQuery({...customQuery, table: e.target.value})}
        />
        
        <h4>Conditions</h4>
        <button onClick={addCondition}>Add Condition</button>
        
        {customQuery.conditions.map((condition, index) => (
          <div key={index} className="condition-row">
            <select
              value={condition.column}
              onChange={(e) => updateCondition(index, 'column', e.target.value)}
            >
              <option value="">Select Column</option>
              <option value="id">ID</option>
              <option value="name">Name</option>
              <option value="email">Email</option>
              <option value="title">Title</option>
              <option value="content">Content</option>
            </select>
            
            <select
              value={condition.operator}
              onChange={(e) => updateCondition(index, 'operator', e.target.value)}
            >
              <option value="=">=</option>
              <option value="!=">!=</option>
              <option value=">">{'>'}</option>
              <option value="<">{'<'}</option>
              <option value=">=">{'>='}</option>
              <option value="<=">{'<='}</option>
              <option value="LIKE">LIKE</option>
            </select>
            
            <input
              type="text"
              placeholder="Value"
              value={condition.value}
              onChange={(e) => updateCondition(index, 'value', e.target.value)}
            />
            
            <select
              value={condition.boolean}
              onChange={(e) => updateCondition(index, 'boolean', e.target.value)}
            >
              <option value="and">AND</option>
              <option value="or">OR</option>
            </select>
            
            <button onClick={() => removeCondition(index)}>Remove</button>
          </div>
        ))}
        
        <button onClick={executeCustomQuery}>Execute Query</button>
        {customResult && (
          <DebugInfoCard 
            title="Custom Query Results" 
            data={customResult} 
          />
        )}
      </div>
    </div>
  );
};

export default QueryBuilder;