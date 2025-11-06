import React, { useState } from 'react';
import Button from './Button.jsx';

const FilterBar = ({ onFilterChange }) => {
  const [filters, setFilters] = useState({
    level: '',
    dateFrom: '',
    dateTo: '',
    search: ''
  });

  const handleInputChange = (field, value) => {
    const newFilters = { ...filters, [field]: value };
    setFilters(newFilters);
    onFilterChange(newFilters);
  };

  const handleReset = () => {
    const resetFilters = {
      level: '',
      dateFrom: '',
      dateTo: '',
      search: ''
    };
    setFilters(resetFilters);
    onFilterChange(resetFilters);
  };

  return (
    <div className="filter-bar">
      <div className="filter-group">
        <label>Level:</label>
        <select 
          value={filters.level} 
          onChange={(e) => handleInputChange('level', e.target.value)}
        >
          <option value="">All Levels</option>
          <option value="error">Error</option>
          <option value="warning">Warning</option>
          <option value="info">Info</option>
          <option value="debug">Debug</option>
        </select>
      </div>
      
      <div className="filter-group">
        <label>From:</label>
        <input
          type="date"
          value={filters.dateFrom}
          onChange={(e) => handleInputChange('dateFrom', e.target.value)}
        />
      </div>
      
      <div className="filter-group">
        <label>To:</label>
        <input
          type="date"
          value={filters.dateTo}
          onChange={(e) => handleInputChange('dateTo', e.target.value)}
        />
      </div>
      
      <div className="filter-group">
        <label>Search:</label>
        <input
          type="text"
          placeholder="Search logs..."
          value={filters.search}
          onChange={(e) => handleInputChange('search', e.target.value)}
        />
      </div>
      
      <Button onClick={handleReset} variant="secondary">
        Reset
      </Button>
    </div>
  );
};

export default FilterBar;