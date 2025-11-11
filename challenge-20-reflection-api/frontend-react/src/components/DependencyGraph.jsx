import React, { useState } from 'react';

const DependencyGraph = () => {
  const [dependencies, setDependencies] = useState({
    'ContainerInterface': ['Container'],
    'ResolverInterface': ['ReflectionResolver'],
    'FactoryInterface': ['ServiceFactory'],
    'LoggerInterface': ['FileLogger', 'DatabaseLogger'],
    'MailerInterface': ['SmtpMailer'],
    'Container': ['ReflectionResolver'],
    'ReflectionResolver': ['Container'],
    'ServiceFactory': ['ReflectionResolver'],
  });

  const [newDependency, setNewDependency] = useState({ parent: '', child: '' });

  const addDependency = () => {
    if (newDependency.parent && newDependency.child) {
      setDependencies(prev => {
        const updated = { ...prev };
        if (!updated[newDependency.parent]) {
          updated[newDependency.parent] = [];
        }
        if (!updated[newDependency.parent].includes(newDependency.child)) {
          updated[newDependency.parent] = [...updated[newDependency.parent], newDependency.child];
        }
        return updated;
      });
      setNewDependency({ parent: '', child: '' });
    }
  };

  const removeDependency = (parent, child) => {
    setDependencies(prev => {
      const updated = { ...prev };
      updated[parent] = updated[parent].filter(item => item !== child);
      if (updated[parent].length === 0) {
        delete updated[parent];
      }
      return updated;
    });
  };

  return (
    <div className="dependency-graph">
      <h2>Dependency Graph</h2>
      
      <div className="dependency-form">
        <input
          type="text"
          placeholder="Parent service"
          value={newDependency.parent}
          onChange={(e) => setNewDependency({...newDependency, parent: e.target.value})}
        />
        <input
          type="text"
          placeholder="Child service"
          value={newDependency.child}
          onChange={(e) => setNewDependency({...newDependency, child: e.target.value})}
        />
        <button onClick={addDependency}>Add Dependency</button>
      </div>

      <div className="graph-container">
        {Object.entries(dependencies).map(([parent, children]) => (
          <div key={parent} className="dependency-group">
            <div className="parent-service">{parent}</div>
            <div className="children-container">
              {children.map((child, index) => (
                <div key={index} className="child-service">
                  <span>{child}</span>
                  <button 
                    className="remove-btn" 
                    onClick={() => removeDependency(parent, child)}
                  >
                    ×
                  </button>
                </div>
              ))}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default DependencyGraph;