import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Home from './pages/index.jsx';
import Batch from './pages/Batch.jsx';
import Optimize from './pages/Optimize.jsx';

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/batch" element={<Batch />} />
        <Route path="/optimize" element={<Optimize />} />
      </Routes>
    </Router>
  );
}

export default App;