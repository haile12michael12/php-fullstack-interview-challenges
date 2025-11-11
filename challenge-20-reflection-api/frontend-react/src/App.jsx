import React from 'react';
import ServiceContainer from './components/ServiceContainer';
import DependencyGraph from './components/DependencyGraph';
import ServiceInspector from './components/ServiceInspector';
import './App.css';

function App() {
  return (
    <div className="App">
      <header className="App-header">
        <h1>Reflection API Demo</h1>
        <p>Exploring PHP Reflection API with Dependency Injection Container</p>
      </header>
      <main>
        <section className="demo-section">
          <ServiceContainer />
        </section>
        <section className="demo-section">
          <DependencyGraph />
        </section>
        <section className="demo-section">
          <ServiceInspector />
        </section>
      </main>
    </div>
  );
}

export default App;