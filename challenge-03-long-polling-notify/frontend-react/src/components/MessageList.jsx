import React from 'react';

function MessageList({ messages }) {
  const formatTimestamp = (timestamp) => {
    return new Date(timestamp * 1000).toLocaleString();
  };

  const getMessageClass = (type) => {
    return `message message-${type}`;
  };

  return (
    <div className="message-list">
      <h2>Notifications</h2>
      {messages.length === 0 ? (
        <p className="no-messages">No notifications received yet.</p>
      ) : (
        <div className="messages">
          {messages.map((message) => (
            <div 
              key={message.id} 
              className={getMessageClass(message.type)}
            >
              <div className="message-header">
                <span className="message-type">{message.type}</span>
                <span className="message-category">{message.category}</span>
                <span className="message-time">{formatTimestamp(message.timestamp)}</span>
              </div>
              <div className="message-content">
                {message.content}
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}

export default MessageList;