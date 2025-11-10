import React from 'react';
import { MemoryProvider } from './context/MemoryContext';
import Dashboard from './pages/Dashboard';
import Trends from './pages/Trends';
import MemoryProfiler from './components/MemoryProfiler';
import MemoryLeakDetector from './components/MemoryLeakDetector';
import MemoryOptimizer from './components/MemoryOptimizer';
import RealTimeMonitor from './components/RealTimeMonitor';
import AlertsPanel from './components/AlertsPanel';
import PerformanceChart from './components/PerformanceChart';
import './index.css';

function App() {
  const [currentPage, setCurrentPage] = React.useState('dashboard');
  
  const renderPage = () => {
    switch (currentPage) {
      case 'dashboard':
        return <Dashboard />;
      case 'trends':
        return <Trends />;
      case 'profiler':
        return <MemoryProfiler />;
      case 'leaks':
        return <MemoryLeakDetector />;
      case 'optimizer':
        return <MemoryOptimizer />;
      default:
        return <Dashboard />;
    }
  };
  
  return (
    <MemoryProvider>
      <div className="app">
        <header className="app-header">
          <h1>Memory Management Dashboard</h1>
          <nav>
            <button 
              className={currentPage === 'dashboard' ? 'active' : ''}
              onClick={() => setCurrentPage('dashboard')}
            >
              Dashboard
            </button>
            <button 
              className={currentPage === 'trends' ? 'active' : ''}
              onClick={() => setCurrentPage('trends')}
            >
              Trends
            </button>
            <button 
              className={currentPage === 'profiler' ? 'active' : ''}
              onClick={() => setCurrentPage('profiler')}
            >
              Profiler
            </button>
            <button 
              className={currentPage === 'leaks' ? 'active' : ''}
              onClick={() => setCurrentPage('leaks')}
            >
              Leak Detector
            </button>
            <button 
              className={currentPage === 'optimizer' ? 'active' : ''}
              onClick={() => setCurrentPage('optimizer')}
            >
              Optimizer
            </button>
          </nav>
        </header>
        
        <main className="app-main">
          {renderPage()}
        </main>
        
        <aside className="app-sidebar">
          <RealTimeMonitor />
          <AlertsPanel />
        </aside>
      </div>
    </MemoryProvider>
  );
}

export default App;