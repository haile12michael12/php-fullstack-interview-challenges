import React from 'react';
import StreamViewer from '../components/StreamViewer';
import IteratorDemo from '../components/IteratorDemo';

const DashboardPage = () => {
  return (
    <div className="dashboard-page">
      <header>
        <h1>Advanced Generators & Iterators Dashboard</h1>
        <p>Interactive demonstrations of advanced PHP features</p>
      </header>
      
      <main>
        <section className="demo-section">
          <StreamViewer />
        </section>
        
        <section className="demo-section">
          <IteratorDemo />
        </section>
      </main>
    </div>
  );
};

export default DashboardPage;