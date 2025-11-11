import React from 'react'
import { BrowserRouter as Router, Routes, Route, Link } from 'react-router-dom'
import Playground from './pages/Playground'
import './App.css'

function App() {
  return (
    <Router>
      <div className="App">
        <nav className="App-nav">
          <ul>
            <li><Link to="/">Traits & Anonymous Classes Playground</Link></li>
          </ul>
        </nav>
        
        <Routes>
          <Route path="/" element={<Playground />} />
        </Routes>
      </div>
    </Router>
  )
}

export default App