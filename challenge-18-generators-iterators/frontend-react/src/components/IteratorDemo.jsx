import React, { useState } from 'react';

const IteratorDemo = () => {
  const [customCollection, setCustomCollection] = useState([]);
  const [filteredData, setFilteredData] = useState([]);
  const [mappedData, setMappedData] = useState([]);
  const [infiniteData, setInfiniteData] = useState([]);

  // Simulate custom collection operations
  const createCollection = () => {
    const data = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
    setCustomCollection(data);
  };

  const filterCollection = () => {
    const filtered = customCollection.filter(item => item % 2 === 0);
    setFilteredData(filtered);
  };

  const mapCollection = () => {
    const mapped = customCollection.map(item => item * 2);
    setMappedData(mapped);
  };

  const generateInfinite = () => {
    // Generate first 10 numbers in sequence (0, 2, 4, 6, 8, 10, 12, 14, 16, 18)
    const sequence = [];
    for (let i = 0; i < 10; i++) {
      sequence.push(i * 2);
    }
    setInfiniteData(sequence);
  };

  return (
    <div className="iterator-demo">
      <h2>Iterator Demo</h2>
      
      <div className="demo-section">
        <h3>Custom Collection</h3>
        <button onClick={createCollection}>Create Collection</button>
        <div className="data-display">
          {customCollection.join(', ')}
        </div>
      </div>
      
      <div className="demo-section">
        <h3>Filter Iterator (Even Numbers)</h3>
        <button onClick={filterCollection}>Filter Collection</button>
        <div className="data-display">
          {filteredData.join(', ')}
        </div>
      </div>
      
      <div className="demo-section">
        <h3>Map Iterator (Double Values)</h3>
        <button onClick={mapCollection}>Map Collection</button>
        <div className="data-display">
          {mappedData.join(', ')}
        </div>
      </div>
      
      <div className="demo-section">
        <h3>Infinite Sequence</h3>
        <button onClick={generateInfinite}>Generate Sequence</button>
        <div className="data-display">
          {infiniteData.join(', ')}
        </div>
      </div>
    </div>
  );
};

export default IteratorDemo;