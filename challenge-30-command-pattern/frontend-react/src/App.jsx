import React, { useState } from 'react'
import './App.css'
import CommandQueue from './components/CommandQueue'
import HistoryViewer from './components/HistoryViewer'

function App() {
  const [activeTab, setActiveTab] = useState('queue')

  return (
    <div className="App">
      <header className="App-header">
        <h1>Command Pattern - Queue Worker</h1>
      </header>
      <main>
        <div className="tabs">
          <button 
            className={activeTab === 'queue' ? 'active' : ''}
            onClick={() => setActiveTab('queue')}
          >
            Command Queue
          </button>
          <button 
            className={activeTab === 'history' ? 'active' : ''}
            onClick={() => setActiveTab('history')}
          >
            History Viewer
          </button>
        </div>
        
        <div className="tab-content">
          {activeTab === 'queue' && <CommandQueue />}
          {activeTab === 'history' && <HistoryViewer />}
        </div>
      </main>
    </div>
  )
}

export default App