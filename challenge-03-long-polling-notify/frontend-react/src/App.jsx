import React, { useState } from 'react';
import NotificationPanel from './components/NotificationPanel';
import ConnectionStatus from './components/ConnectionStatus';
import MessageList from './components/MessageList';
import longPollingService from './services/longPollingService';

function App() {
  const [clientId] = useState(() => 'client_' + Math.random().toString(36).substr(2, 9));
  const [messages, setMessages] = useState([]);
  const [connectionStatus, setConnectionStatus] = useState('disconnected');
  const [serverStatus, setServerStatus] = useState(null);

  // Function to start long polling
  const startPolling = async () => {
    setConnectionStatus('connecting');
    
    try {
      const response = await longPollingService.poll(clientId);
      setConnectionStatus('connected');
      
      if (response.messages && response.messages.length > 0) {
        setMessages(prev => [...response.messages, ...prev]);
      }
      
      // Continue polling
      startPolling();
    } catch (error) {
      setConnectionStatus('error');
      console.error('Polling error:', error);
      
      // Retry after a delay
      setTimeout(startPolling, 5000);
    }
  };

  // Function to fetch server status
  const fetchServerStatus = async () => {
    try {
      const status = await longPollingService.getServerStatus();
      setServerStatus(status);
    } catch (error) {
      console.error('Failed to fetch server status:', error);
    }
  };

  // Function to send a test notification
  const sendTestNotification = async () => {
    try {
      await longPollingService.sendNotification({
        type: 'info',
        content: 'This is a test notification',
        category: 'general'
      });
    } catch (error) {
      console.error('Failed to send notification:', error);
    }
  };

  // Start polling when component mounts
  React.useEffect(() => {
    startPolling();
    fetchServerStatus();
    
    // Refresh server status every 10 seconds
    const interval = setInterval(fetchServerStatus, 10000);
    return () => clearInterval(interval);
  }, []);

  return (
    <div className="App">
      <header>
        <h1>Long Polling Notification System</h1>
      </header>
      
      <main>
        <ConnectionStatus 
          status={connectionStatus} 
          serverStatus={serverStatus}
        />
        
        <NotificationPanel 
          onSendNotification={sendTestNotification}
        />
        
        <MessageList messages={messages} />
      </main>
    </div>
  );
}

export default App;