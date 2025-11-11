import React from 'react';
import DataProcessor from '../components/DataProcessor';
import PerformanceChart from '../components/PerformanceChart';

const HomePage = () => {
  return (
    <div className="home-page">
      <header>
        <h1>Generators & Iterators Demo</h1>
        <p>Explore the power of PHP generators and iterators</p>
      </header>
      
      <main>
        <section className="demo-section">
          <DataProcessor />
        </section>
        
        <section className="demo-section">
          <PerformanceChart />
        </section>
      </main>
    </div>
  );
};

export default HomePage;