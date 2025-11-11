import React from 'react';
import EntityExplorer from '../components/EntityExplorer';

const EntityPage = () => {
  return (
    <div className="entity-page">
      <h1>Entity Management</h1>
      <p>Work with ORM entities using PHP magic methods.</p>
      
      <EntityExplorer />
    </div>
  );
};

export default EntityPage;