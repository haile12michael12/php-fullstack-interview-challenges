import { useState, useEffect, useRef } from 'react';

const useWebSocket = (url) => {
  const [ws, setWs] = useState(null);
  const [isConnected, setIsConnected] = useState(false);
  const [messages, setMessages] = useState([]);
  const [error, setError] = useState(null);
  const messageQueue = useRef([]);

  // Connect to WebSocket server
  useEffect(() => {
    if (!url) return;

    const websocket = new WebSocket(url);
    
    websocket.onopen = () => {
      setIsConnected(true);
      setError(null);
      
      // Send any queued messages
      messageQueue.current.forEach(msg => {
        websocket.send(JSON.stringify(msg));
      });
      messageQueue.current = [];
    };

    websocket.onmessage = (event) => {
      try {
        const data = JSON.parse(event.data);
        setMessages(prev => [...prev, data]);
      } catch (e) {
        console.error('Error parsing WebSocket message:', e);
      }
    };

    websocket.onclose = () => {
      setIsConnected(false);
    };

    websocket.onerror = (err) => {
      setError(err);
      setIsConnected(false);
    };

    setWs(websocket);

    // Cleanup function
    return () => {
      if (websocket) {
        websocket.close();
      }
    };
  }, [url]);

  // Send message through WebSocket
  const sendMessage = (message) => {
    if (ws && isConnected) {
      ws.send(JSON.stringify(message));
    } else {
      // Queue message if not connected
      messageQueue.current.push(message);
    }
  };

  // Subscribe to metrics
  const subscribeToMetrics = (interval = 1000) => {
    sendMessage({
      type: 'subscribe_metrics',
      data: { interval },
      correlation_id: Date.now().toString()
    });
  };

  // Unsubscribe from metrics
  const unsubscribeFromMetrics = () => {
    sendMessage({
      type: 'unsubscribe_metrics',
      correlation_id: Date.now().toString()
    });
  };

  // Get current metrics
  const getCurrentMetrics = () => {
    sendMessage({
      type: 'get_current_metrics',
      correlation_id: Date.now().toString()
    });
  };

  // Send ping
  const sendPing = () => {
    sendMessage({
      type: 'ping',
      correlation_id: Date.now().toString()
    });
  };

  return {
    isConnected,
    messages,
    error,
    sendMessage,
    subscribeToMetrics,
    unsubscribeFromMetrics,
    getCurrentMetrics,
    sendPing
  };
};

export default useWebSocket;