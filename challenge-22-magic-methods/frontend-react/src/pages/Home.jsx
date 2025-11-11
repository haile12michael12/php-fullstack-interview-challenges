import React from 'react';
import { Link } from 'react-router-dom';

const Home = () => {
  return (
    <div className="home-page">
      <h1>Challenge 22: Magic Methods</h1>
      <p>Welcome to the Magic Methods challenge demonstration.</p>
      
      <div className="navigation-links">
        <Link to="/magic" className="nav-link">
          <h2>Magic Methods Demo</h2>
          <p>Explore fluent interfaces, dynamic proxies, and method interceptors</p>
        </Link>
        
        <Link to="/entities" className="nav-link">
          <h2>Entity Explorer</h2>
          <p>Work with ORM entities and magic methods</p>
        </Link>
        
        <Link to="/query" className="nav-link">
          <h2>Query Builder</h2>
          <p>Build and execute dynamic queries</p>
        </Link>
      </div>
      
      <div className="challenge-info">
        <h3>About This Challenge</h3>
        <p>This challenge demonstrates the power of PHP magic methods:</p>
        <ul>
          <li><code>__construct</code> - Object initialization</li>
          <li><code>__get</code> and <code>__set</code> - Property access</li>
          <li><code>__call</code> and <code>__callStatic</code> - Method interception</li>
          <li><code>__isset</code> and <code>__unset</code> - Property existence</li>
          <li><code>__toString</code> - Object string representation</li>
          <li><code>__invoke</code> - Callable objects</li>
          <li><code>__clone</code> - Object cloning</li>
          <li><code>__sleep</code> and <code>__wakeup</code> - Serialization</li>
          <li><code>__debugInfo</code> - Debug information</li>
        </ul>
      </div>
    </div>
  );
};

export default Home;