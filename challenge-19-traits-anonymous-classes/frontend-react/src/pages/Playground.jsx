import React from 'react';
import TraitDemo from '../components/TraitDemo';
import AnonymousClassDemo from '../components/AnonymousClassDemo';

const Playground = () => {
  return (
    <div className="playground">
      <header>
        <h1>Traits & Anonymous Classes Playground</h1>
        <p>Interactive demonstration of PHP traits and anonymous classes</p>
      </header>
      
      <main>
        <section className="demo-section">
          <TraitDemo />
        </section>
        
        <section className="demo-section">
          <AnonymousClassDemo />
        </section>
      </main>
    </div>
  );
};

export default Playground;