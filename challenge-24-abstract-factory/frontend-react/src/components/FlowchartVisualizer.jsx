import React from 'react';

const FlowchartVisualizer = () => {
  return (
    <div className="flowchart-visualizer">
      <h3>Abstract Factory Flowchart</h3>
      <div className="flowchart">
        <div className="flowchart-row">
          <div className="flowchart-node client">
            <div className="node-label">Client</div>
          </div>
        </div>
        
        <div className="flowchart-row">
          <div className="flowchart-node factory-interface">
            <div className="node-label">DatabaseFactoryInterface</div>
          </div>
        </div>
        
        <div className="flowchart-row">
          <div className="flowchart-node factory concrete-factory">
            <div className="node-label">MySqlFactory</div>
          </div>
          <div className="flowchart-node factory concrete-factory">
            <div className="node-label">PostgreSqlFactory</div>
          </div>
          <div className="flowchart-node factory concrete-factory">
            <div className="node-label">SqliteFactory</div>
          </div>
        </div>
        
        <div className="flowchart-row">
          <div className="flowchart-node product connection">
            <div className="node-label">MySqlConnection</div>
          </div>
          <div className="flowchart-node product connection">
            <div className="node-label">PostgreSqlConnection</div>
          </div>
          <div className="flowchart-node product connection">
            <div className="node-label">SqliteConnection</div>
          </div>
        </div>
        
        <div className="flowchart-row">
          <div className="flowchart-node product querybuilder">
            <div className="node-label">MySqlQueryBuilder</div>
          </div>
          <div className="flowchart-node product querybuilder">
            <div className="node-label">PostgreSqlQueryBuilder</div>
          </div>
          <div className="flowchart-node product querybuilder">
            <div className="node-label">SqliteQueryBuilder</div>
          </div>
        </div>
      </div>
      
      <div className="flowchart-legend">
        <h4>Legend</h4>
        <div className="legend-item">
          <div className="legend-color client"></div>
          <span>Client</span>
        </div>
        <div className="legend-item">
          <div className="legend-color factory-interface"></div>
          <span>Factory Interface</span>
        </div>
        <div className="legend-item">
          <div className="legend-color concrete-factory"></div>
          <span>Concrete Factories</span>
        </div>
        <div className="legend-item">
          <div className="legend-color connection"></div>
          <span>Connection Products</span>
        </div>
        <div className="legend-item">
          <div className="legend-color querybuilder"></div>
          <span>QueryBuilder Products</span>
        </div>
      </div>
    </div>
  );
};

export default FlowchartVisualizer;