import React from 'react';
import { BrowserRouter as Router, Routes, Route, Link } from 'react-router-dom';
import Home from './pages/Home';
import EntityPage from './pages/EntityPage';
import QueryPage from './pages/QueryPage';
import MagicMethodsDemo from './components/MagicMethodsDemo';
import './App.css';

function App() {
  return (
    <Router>
      <div className="App">
        <header className="App-header">
          <h1>Challenge 22: Magic Methods</h1>
          <nav>
            <Link to="/">Home</Link>
            <Link to="/magic">Magic Methods</Link>
            <Link to="/entities">Entities</Link>
            <Link to="/query">Query Builder</Link>
          </nav>
        </header>
        <main>
          <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/magic" element={<MagicMethodsDemo />} />
            <Route path="/entities" element={<EntityPage />} />
            <Route path="/query" element={<QueryPage />} />
          </Routes>
        </main>
      </div>
    </Router>
  );
}

export default App;