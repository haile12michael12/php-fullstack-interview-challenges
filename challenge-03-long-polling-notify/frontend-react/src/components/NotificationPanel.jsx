import React, { useState } from 'react';

function NotificationPanel({ onSendNotification }) {
  const [notificationType, setNotificationType] = useState('info');
  const [notificationContent, setNotificationContent] = useState('');
  const [notificationCategory, setNotificationCategory] = useState('general');

  const handleSubmit = (e) => {
    e.preventDefault();
    
    if (!notificationContent.trim()) {
      return;
    }
    
    onSendNotification({
      type: notificationType,
      content: notificationContent,
      category: notificationCategory
    });
    
    // Clear form
    setNotificationContent('');
  };

  return (
    <div className="notification-panel">
      <h2>Send Notification</h2>
      <form onSubmit={handleSubmit}>
        <div>
          <label>Type:</label>
          <select 
            value={notificationType} 
            onChange={(e) => setNotificationType(e.target.value)}
          >
            <option value="info">Info</option>
            <option value="warning">Warning</option>
            <option value="error">Error</option>
            <option value="success">Success</option>
          </select>
        </div>
        
        <div>
          <label>Category:</label>
          <select 
            value={notificationCategory} 
            onChange={(e) => setNotificationCategory(e.target.value)}
          >
            <option value="general">General</option>
            <option value="system">System</option>
            <option value="user">User</option>
            <option value="security">Security</option>
          </select>
        </div>
        
        <div>
          <label>Content:</label>
          <textarea
            value={notificationContent}
            onChange={(e) => setNotificationContent(e.target.value)}
            placeholder="Enter notification content"
            rows="3"
          />
        </div>
        
        <button type="submit">Send Notification</button>
      </form>
    </div>
  );
}

export default NotificationPanel;