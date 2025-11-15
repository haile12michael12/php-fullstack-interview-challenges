import React from 'react';
import useConnectionStore from '../store/connectionStore';

const ConnectionStatusCard = () => {
  const connections = useConnectionStore(state => state.connections);
  const activeConnection = useConnectionStore(state => state.activeConnection);
  
  const connectionCount = Object.keys(connections).length;
  
  return (
    <div className="bg-white rounded-lg shadow-md p-6 mb-6">
      <h2 className="text-xl font-bold mb-4">Connection Status</h2>
      
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div className="bg-blue-50 rounded-lg p-4">
          <div className="text-2xl font-bold text-blue-800">{connectionCount}</div>
          <div className="text-sm text-blue-600">Active Connections</div>
        </div>
        
        <div className="bg-green-50 rounded-lg p-4">
          <div className="text-2xl font-bold text-green-800">
            {activeConnection ? 1 : 0}
          </div>
          <div className="text-sm text-green-600">Selected Connection</div>
        </div>
        
        <div className="bg-purple-50 rounded-lg p-4">
          <div className="text-2xl font-bold text-purple-800">
            {Object.keys(connections).filter(id => connections[id].type === 'mysql').length}
          </div>
          <div className="text-sm text-purple-600">MySQL Connections</div>
        </div>
      </div>
      
      {connectionCount > 0 && (
        <div className="mt-4">
          <h3 className="font-medium mb-2">Your Connections</h3>
          <div className="space-y-2">
            {Object.entries(connections).map(([id, connection]) => (
              <div 
                key={id}
                className={`p-3 rounded-md border ${
                  id === activeConnection 
                    ? 'border-blue-500 bg-blue-50' 
                    : 'border-gray-200'
                }`}
              >
                <div className="flex justify-between items-center">
                  <div>
                    <div className="font-medium">
                      {connection.config.database || 'Unnamed Database'}
                    </div>
                    <div className="text-sm text-gray-600">
                      {connection.type} • {connection.config.host}:{connection.config.port}
                    </div>
                  </div>
                  <div className="text-xs text-gray-500">
                    {new Date(connection.createdAt).toLocaleTimeString()}
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
};

export default ConnectionStatusCard;