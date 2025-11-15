import React, { useState } from 'react';

const DatabaseSelector = ({ onDatabaseSelect }) => {
  const [selectedDatabase, setSelectedDatabase] = useState('');

  const databases = [
    { id: 'mysql', name: 'MySQL', icon: 'mysql-icon' },
    { id: 'postgresql', name: 'PostgreSQL', icon: 'postgresql-icon' },
    { id: 'sqlite', name: 'SQLite', icon: 'sqlite-icon' },
    { id: 'mongodb', name: 'MongoDB', icon: 'mongodb-icon' },
    { id: 'redis', name: 'Redis', icon: 'redis-icon' }
  ];

  const handleSelect = (databaseId) => {
    setSelectedDatabase(databaseId);
    if (onDatabaseSelect) {
      onDatabaseSelect(databaseId);
    }
  };

  return (
    <div className="database-selector">
      <h3>Select Database</h3>
      <div className="database-grid">
        {databases.map(database => (
          <div 
            key={database.id}
            className={`database-card ${selectedDatabase === database.id ? 'selected' : ''}`}
            onClick={() => handleSelect(database.id)}
          >
            <div className="database-icon">{database.icon}</div>
            <div className="database-name">{database.name}</div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default DatabaseSelector;