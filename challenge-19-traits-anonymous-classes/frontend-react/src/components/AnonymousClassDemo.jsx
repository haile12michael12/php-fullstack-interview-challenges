import React, { useState } from 'react';
import { oopService } from '../services/oopService';

const AnonymousClassDemo = () => {
  const [userData, setUserData] = useState({ name: '', email: '' });
  const [createdUser, setCreatedUser] = useState(null);
  const [systemStats, setSystemStats] = useState(null);

  const handleCreateUser = async () => {
    try {
      const result = await oopService.createUser(userData);
      setCreatedUser(result);
    } catch (error) {
      console.error('Error creating user:', error);
    }
  };

  const handleGetStats = async () => {
    try {
      const result = await oopService.getSystemStats();
      setSystemStats(result);
    } catch (error) {
      console.error('Error getting stats:', error);
    }
  };

  return (
    <div className="anonymous-class-demo">
      <h2>Anonymous Classes Demo</h2>
      
      <div className="demo-section">
        <h3>User Creation</h3>
        <div>
          <input 
            type="text" 
            value={userData.name} 
            onChange={(e) => setUserData({...userData, name: e.target.value})} 
            placeholder="Enter name"
          />
          <input 
            type="email" 
            value={userData.email} 
            onChange={(e) => setUserData({...userData, email: e.target.value})} 
            placeholder="Enter email"
          />
          <button onClick={handleCreateUser}>Create User</button>
          {createdUser && (
            <div>
              <p>User created successfully!</p>
              <p>ID: {createdUser.id}</p>
              <p>Name: {createdUser.name}</p>
              <p>Email: {createdUser.email}</p>
              <p>Created: {createdUser.created_at}</p>
            </div>
          )}
        </div>
      </div>

      <div className="demo-section">
        <h3>System Statistics</h3>
        <button onClick={handleGetStats}>Get System Stats</button>
        {systemStats && (
          <div>
            <h4>Users ({systemStats.users.length})</h4>
            <ul>
              {systemStats.users.map((user, index) => (
                <li key={index}>
                  {user.name} ({user.email}) - Created: {user.created_at}
                </li>
              ))}
            </ul>
            
            <h4>Cache Stats</h4>
            <p>Count: {systemStats.cache_stats.count}</p>
            <p>Keys: {systemStats.cache_stats.keys.join(', ')}</p>
            
            <h4>Available Strategies</h4>
            <ul>
              {systemStats.strategies.map((strategy, index) => (
                <li key={index}>{strategy}</li>
              ))}
            </ul>
          </div>
        )}
      </div>
    </div>
  );
};

export default AnonymousClassDemo;