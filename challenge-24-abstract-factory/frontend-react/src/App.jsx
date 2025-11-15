import React, { useState } from 'react';
import './App.css';
import AbstractFactoryDemo from './components/AbstractFactoryDemo';
import DatabaseSelector from './components/DatabaseSelector';
import QueryExecutor from './components/QueryExecutor';
import FlowchartVisualizer from './components/FlowchartVisualizer';
import AuthWidget from './components/AuthWidget';

function App() {
  const [activeTab, setActiveTab] = useState('demo');

  return (
    <div className="App">
      <header className="App-header">
        <h1>Challenge 24: Abstract Factory Pattern</h1>
        <nav className="main-nav">
          <button 
            className={activeTab === 'demo' ? 'active' : ''}
            onClick={() => setActiveTab('demo')}
          >
            Factory Demo
          </button>
          <button 
            className={activeTab === 'selector' ? 'active' : ''}
            onClick={() => setActiveTab('selector')}
          >
            Database Selector
          </button>
          <button 
            className={activeTab === 'query' ? 'active' : ''}
            onClick={() => setActiveTab('query')}
          >
            Query Executor
          </button>
          <button 
            className={activeTab === 'flowchart' ? 'active' : ''}
            onClick={() => setActiveTab('flowchart')}
          >
            Flowchart
          </button>
          <button 
            className={activeTab === 'auth' ? 'active' : ''}
            onClick={() => setActiveTab('auth')}
          >
            Auth
          </button>
        </nav>
      </header>
      
      <main className="App-main">
        {activeTab === 'demo' && <AbstractFactoryDemo />}
        {activeTab === 'selector' && <DatabaseSelector />}
        {activeTab === 'query' && <QueryExecutor />}
        {activeTab === 'flowchart' && <FlowchartVisualizer />}
        {activeTab === 'auth' && <AuthWidget />}
      </main>
    </div>
  );
}

export default App;