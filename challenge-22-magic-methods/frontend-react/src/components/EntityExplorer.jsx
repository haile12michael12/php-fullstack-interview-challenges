import React, { useState, useEffect } from 'react';
import DebugInfoCard from './DebugInfoCard';

const EntityExplorer = () => {
  const [users, setUsers] = useState([]);
  const [selectedUser, setSelectedUser] = useState(null);
  const [newUser, setNewUser] = useState({ name: '', email: '', age: '' });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const fetchUsers = async () => {
    setLoading(true);
    setError(null);
    try {
      const response = await fetch('/api/entities/users');
      const data = await response.json();
      setUsers(data.data || []);
    } catch (err) {
      setError('Failed to fetch users');
    } finally {
      setLoading(false);
    }
  };

  const createUser = async () => {
    setLoading(true);
    setError(null);
    try {
      const response = await fetch('/api/entities/users', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(newUser),
      });
      
      const data = await response.json();
      if (data.status === 'success') {
        setNewUser({ name: '', email: '', age: '' });
        fetchUsers();
      } else {
        setError(data.message || 'Failed to create user');
      }
    } catch (err) {
      setError('Failed to create user');
    } finally {
      setLoading(false);
    }
  };

  const deleteUser = async (id) => {
    setLoading(true);
    setError(null);
    try {
      const response = await fetch(`/api/entities/users/${id}`, {
        method: 'DELETE',
      });
      
      const data = await response.json();
      if (data.status === 'success') {
        fetchUsers();
      } else {
        setError(data.message || 'Failed to delete user');
      }
    } catch (err) {
      setError('Failed to delete user');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchUsers();
  }, []);

  return (
    <div className="entity-explorer">
      <h2>Entity Explorer</h2>
      
      {error && <div className="error">{error}</div>}
      {loading && <div className="loading">Loading...</div>}
      
      <div className="user-form">
        <h3>Create New User</h3>
        <input
          type="text"
          placeholder="Name"
          value={newUser.name}
          onChange={(e) => setNewUser({...newUser, name: e.target.value})}
        />
        <input
          type="email"
          placeholder="Email"
          value={newUser.email}
          onChange={(e) => setNewUser({...newUser, email: e.target.value})}
        />
        <input
          type="number"
          placeholder="Age"
          value={newUser.age}
          onChange={(e) => setNewUser({...newUser, age: e.target.value})}
        />
        <button onClick={createUser}>Create User</button>
      </div>
      
      <div className="user-list">
        <h3>Users</h3>
        <button onClick={fetchUsers}>Refresh</button>
        <ul>
          {users.map(user => (
            <li key={user.id} className="user-item">
              <span>{user.name} ({user.email}) - Age: {user.age}</span>
              <button onClick={() => setSelectedUser(user)}>View</button>
              <button onClick={() => deleteUser(user.id)}>Delete</button>
            </li>
          ))}
        </ul>
      </div>
      
      {selectedUser && (
        <DebugInfoCard 
          title={`User Details: ${selectedUser.name}`} 
          data={selectedUser} 
          onClose={() => setSelectedUser(null)}
        />
      )}
    </div>
  );
};

export default EntityExplorer;