import React from 'react';
import CacheDashboard from './components/CacheDashboard';
import './App.css';

function App() {
  return (
    <div className="App">
      <header className="App-header">
        <h1>Advanced Caching Dashboard</h1>
        <p>Monitor and manage your caching system</p>
      </header>
      <main>
        <CacheDashboard />
      </main>
    </div>
  );
}

export default App;