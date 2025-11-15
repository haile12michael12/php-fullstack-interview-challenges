import React, { useState, useEffect } from 'react';
import commandService from '../services/commandService';

const HistoryViewer = () => {
  const [history, setHistory] = useState([]);
  const [undoneCommands, setUndoneCommands] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetchHistory();
  }, []);

  const fetchHistory = async () => {
    setLoading(true);
    setError(null);
    
    try {
      const [historyData, undoneData] = await Promise.all([
        commandService.getCommandHistory(),
        commandService.getUndoneCommands()
      ]);
      
      setHistory(historyData);
      setUndoneCommands(undoneData);
    } catch (err) {
      setError('Failed to fetch command history');
    } finally {
      setLoading(false);
    }
  };

  const handleUndo = async () => {
    try {
      await commandService.undoLastCommand();
      fetchHistory(); // Refresh history
    } catch (err) {
      setError('Failed to undo command');
    }
  };

  const handleRedo = async () => {
    try {
      await commandService.redoLastCommand();
      fetchHistory(); // Refresh history
    } catch (err) {
      setError('Failed to redo command');
    }
  };

  const handleClearHistory = async () => {
    try {
      await commandService.clearHistory();
      setHistory([]);
      setUndoneCommands([]);
    } catch (err) {
      setError('Failed to clear history');
    }
  };

  const formatMetadata = (metadata) => {
    return Object.entries(metadata)
      .filter(([key]) => !['created_at', 'executed_at', 'undone_at', 'id'].includes(key))
      .map(([key, value]) => (
        <span key={key} className="metadata-item">
          <strong>{key}:</strong> {typeof value === 'object' ? JSON.stringify(value) : value}
        </span>
      ));
  };

  return (
    <div className="history-viewer">
      <div className="header">
        <h2>Command History</h2>
        <div className="actions">
          <button onClick={fetchHistory} disabled={loading} className="refresh-button">
            {loading ? 'Refreshing...' : 'Refresh'}
          </button>
          <button onClick={handleUndo} disabled={history.length === 0} className="undo-button">
            Undo
          </button>
          <button onClick={handleRedo} disabled={undoneCommands.length === 0} className="redo-button">
            Redo
          </button>
          <button onClick={handleClearHistory} disabled={history.length === 0 && undoneCommands.length === 0} className="clear-button">
            Clear History
          </button>
        </div>
      </div>
      
      {error && <div className="error-message">{error}</div>}
      
      <div className="history-section">
        <h3>Executed Commands ({history.length})</h3>
        {history.length === 0 ? (
          <p className="empty-message">No executed commands</p>
        ) : (
          <div className="command-list">
            {history.map((command, index) => (
              <div key={command.metadata.id || index} className="command-item executed">
                <div className="command-header">
                  <h4>{command.name}</h4>
                  <span className="command-status executed">Executed</span>
                </div>
                <p className="command-description">{command.description}</p>
                <div className="command-metadata">
                  {formatMetadata(command.metadata)}
                </div>
                <div className="command-timestamps">
                  <span>Created: {command.metadata.created_at}</span>
                  {command.metadata.executed_at && (
                    <span>Executed: {command.metadata.executed_at}</span>
                  )}
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
      
      <div className="history-section">
        <h3>Undone Commands ({undoneCommands.length})</h3>
        {undoneCommands.length === 0 ? (
          <p className="empty-message">No undone commands</p>
        ) : (
          <div className="command-list">
            {undoneCommands.map((command, index) => (
              <div key={command.metadata.id || index} className="command-item undone">
                <div className="command-header">
                  <h4>{command.name}</h4>
                  <span className="command-status undone">Undone</span>
                </div>
                <p className="command-description">{command.description}</p>
                <div className="command-metadata">
                  {formatMetadata(command.metadata)}
                </div>
                <div className="command-timestamps">
                  <span>Created: {command.metadata.created_at}</span>
                  {command.metadata.undone_at && (
                    <span>Undone: {command.metadata.undone_at}</span>
                  )}
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
};

export default HistoryViewer;