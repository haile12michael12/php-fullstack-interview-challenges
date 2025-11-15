import React, { useState } from 'react';
import useConnectionStore from '../store/connectionStore';

const DatabaseSelector = () => {
  const [dbType, setDbType] = useState('mysql');
  const [dbConfig, setDbConfig] = useState({
    host: 'localhost',
    port: 3306,
    database: '',
    username: '',
    password: ''
  });
  
  const addConnection = useConnectionStore(state => state.addConnection);
  const setActiveConnection = useConnectionStore(state => state.setActiveConnection);
  
  const handleConfigChange = (field, value) => {
    setDbConfig(prev => ({
      ...prev,
      [field]: value
    }));
  };
  
  const handleTestConnection = async () => {
    // In a real implementation, this would call the API to test the connection
    console.log('Testing connection:', { dbType, dbConfig });
    
    // For demo purposes, we'll just add the connection to the store
    const connectionId = `${dbType}-${Date.now()}`;
    addConnection(connectionId, {
      id: connectionId,
      type: dbType,
      config: dbConfig,
      createdAt: new Date().toISOString()
    });
    
    setActiveConnection(connectionId);
  };
  
  return (
    <div className="bg-white rounded-lg shadow-md p-6 mb-6">
      <h2 className="text-xl font-bold mb-4">Database Connection</h2>
      
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">Database Type</label>
          <select
            value={dbType}
            onChange={(e) => setDbType(e.target.value)}
            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="mysql">MySQL</option>
            <option value="postgres">PostgreSQL</option>
            <option value="sqlite">SQLite</option>
          </select>
        </div>
        
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">Host</label>
          <input
            type="text"
            value={dbConfig.host}
            onChange={(e) => handleConfigChange('host', e.target.value)}
            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="localhost"
          />
        </div>
        
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">Port</label>
          <input
            type="number"
            value={dbConfig.port}
            onChange={(e) => handleConfigChange('port', parseInt(e.target.value))}
            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="3306"
          />
        </div>
        
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">Database Name</label>
          <input
            type="text"
            value={dbConfig.database}
            onChange={(e) => handleConfigChange('database', e.target.value)}
            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="database_name"
          />
        </div>
        
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">Username</label>
          <input
            type="text"
            value={dbConfig.username}
            onChange={(e) => handleConfigChange('username', e.target.value)}
            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="username"
          />
        </div>
        
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input
            type="password"
            value={dbConfig.password}
            onChange={(e) => handleConfigChange('password', e.target.value)}
            className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="password"
          />
        </div>
      </div>
      
      <div className="flex space-x-3">
        <button
          onClick={handleTestConnection}
          className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
          Test Connection
        </button>
      </div>
    </div>
  );
};

export default DatabaseSelector;