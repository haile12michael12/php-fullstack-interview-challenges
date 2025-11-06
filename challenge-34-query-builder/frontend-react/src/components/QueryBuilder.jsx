import React, { useState } from 'react';
import './QueryBuilder.css';

const QueryBuilder = ({ onQuerySubmit }) => {
  const [queryType, setQueryType] = useState('select');
  const [table, setTable] = useState('');
  const [columns, setColumns] = useState('*');
  const [conditions, setConditions] = useState([{ column: '', operator: '=', value: '' }]);
  const [orderBy, setOrderBy] = useState('');
  const [limit, setLimit] = useState('');

  const addCondition = () => {
    setConditions([...conditions, { column: '', operator: '=', value: '' }]);
  };

  const updateCondition = (index, field, value) => {
    const updatedConditions = [...conditions];
    updatedConditions[index][field] = value;
    setConditions(updatedConditions);
  };

  const removeCondition = (index) => {
    if (conditions.length > 1) {
      const updatedConditions = conditions.filter((_, i) => i !== index);
      setConditions(updatedConditions);
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    
    const queryData = {
      type: queryType,
      table,
      columns: columns.split(',').map(col => col.trim()),
      conditions: conditions.filter(cond => cond.column && cond.value),
      orderBy,
      limit: limit ? parseInt(limit) : null
    };

    onQuerySubmit(queryData);
  };

  return (
    <div className="query-builder">
      <h2>Query Builder</h2>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Query Type:</label>
          <select value={queryType} onChange={(e) => setQueryType(e.target.value)}>
            <option value="select">SELECT</option>
            <option value="insert">INSERT</option>
            <option value="update">UPDATE</option>
            <option value="delete">DELETE</option>
          </select>
        </div>

        <div className="form-group">
          <label>Table:</label>
          <input
            type="text"
            value={table}
            onChange={(e) => setTable(e.target.value)}
            placeholder="Enter table name"
            required
          />
        </div>

        {queryType === 'select' && (
          <div className="form-group">
            <label>Columns (comma separated):</label>
            <input
              type="text"
              value={columns}
              onChange={(e) => setColumns(e.target.value)}
              placeholder="Enter columns (e.g., id, name, email)"
            />
          </div>
        )}

        <div className="conditions-section">
          <label>Conditions:</label>
          {conditions.map((condition, index) => (
            <div key={index} className="condition-row">
              <input
                type="text"
                placeholder="Column"
                value={condition.column}
                onChange={(e) => updateCondition(index, 'column', e.target.value)}
              />
              <select
                value={condition.operator}
                onChange={(e) => updateCondition(index, 'operator', e.target.value)}
              >
                <option value="=">=</option>
                <option value="!=">!=</option>
                <option value=">">{'>'}</option>
                <option value="<">{'<'}</option>
                <option value=">=">{'>='}</option>
                <option value="<=">{'<='}</option>
                <option value="LIKE">LIKE</option>
              </select>
              <input
                type="text"
                placeholder="Value"
                value={condition.value}
                onChange={(e) => updateCondition(index, 'value', e.target.value)}
              />
              <button type="button" onClick={() => removeCondition(index)}>
                Remove
              </button>
            </div>
          ))}
          <button type="button" onClick={addCondition}>
            Add Condition
          </button>
        </div>

        {queryType === 'select' && (
          <>
            <div className="form-group">
              <label>Order By:</label>
              <input
                type="text"
                value={orderBy}
                onChange={(e) => setOrderBy(e.target.value)}
                placeholder="Enter column name"
              />
            </div>

            <div className="form-group">
              <label>Limit:</label>
              <input
                type="number"
                value={limit}
                onChange={(e) => setLimit(e.target.value)}
                placeholder="Enter limit"
              />
            </div>
          </>
        )}

        <button type="submit">Execute Query</button>
      </form>
    </div>
  );
};

export default QueryBuilder;