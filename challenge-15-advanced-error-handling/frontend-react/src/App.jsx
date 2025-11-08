import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { ErrorProvider } from './context/ErrorContext';
import ErrorBoundary from './components/ErrorBoundary';
import HomePage from './pages/HomePage';
import ErrorDemoPage from './pages/ErrorDemoPage';
import MaintenancePage from './pages/MaintenancePage';
import ErrorPage from './components/ErrorPage';
import NotificationToast from './components/NotificationToast';
import './index.css';

function App() {
  return (
    <ErrorProvider>
      <Router>
        <ErrorBoundary>
          <div className="App">
            <header className="app-header">
              <h1>Advanced Error Handling Demo</h1>
            </header>
            
            <main className="app-main">
              <Routes>
                <Route path="/" element={<HomePage />} />
                <Route path="/error-demo" element={<ErrorDemoPage />} />
                <Route path="/maintenance" element={<MaintenancePage />} />
                <Route path="/error" element={<ErrorPage />} />
              </Routes>
            </main>
            
            <NotificationToast />
          </div>
        </ErrorBoundary>
      </Router>
    </ErrorProvider>
  );
}

export default App;