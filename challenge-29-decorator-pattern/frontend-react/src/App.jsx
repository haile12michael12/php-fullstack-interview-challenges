import React, { useState } from 'react'
import './App.css'
import MiddlewareDemo from './components/MiddlewareDemo'
import PipelineVisualizer from './components/PipelineVisualizer'

function App() {
  const [activeTab, setActiveTab] = useState('demo')

  return (
    <div className="App">
      <header className="App-header">
        <h1>Decorator Pattern - Middleware Pipeline</h1>
      </header>
      <main>
        <div className="tabs">
          <button 
            className={activeTab === 'demo' ? 'active' : ''}
            onClick={() => setActiveTab('demo')}
          >
            Middleware Demo
          </button>
          <button 
            className={activeTab === 'visualizer' ? 'active' : ''}
            onClick={() => setActiveTab('visualizer')}
          >
            Pipeline Visualizer
          </button>
        </div>
        
        <div className="tab-content">
          {activeTab === 'demo' && <MiddlewareDemo />}
          {activeTab === 'visualizer' && <PipelineVisualizer />}
        </div>
      </main>
    </div>
  )
}

export default App