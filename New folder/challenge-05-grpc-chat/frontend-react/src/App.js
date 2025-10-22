import React, { useState, useEffect } from 'react';
import { Box, CssBaseline, ThemeProvider, createTheme } from '@mui/material';
import LoginScreen from './components/LoginScreen';
import ChatScreen from './components/ChatScreen';
import { ChatServiceClient } from './generated/chat_grpc_web_pb';
import { Empty } from './generated/chat_pb';

const theme = createTheme({
  palette: {
    primary: {
      main: '#1976d2',
    },
    secondary: {
      main: '#dc004e',
    },
  },
});

function App() {
  const [user, setUser] = useState(null);
  const [client, setClient] = useState(null);
  const [onlineUsers, setOnlineUsers] = useState([]);

  useEffect(() => {
    // Create gRPC client
    const chatClient = new ChatServiceClient('http://localhost:9001');
    setClient(chatClient);

    // Poll for online users every 10 seconds
    const interval = setInterval(() => {
      if (user) {
        chatClient.getOnlineUsers(new Empty(), {}, (err, response) => {
          if (!err) {
            const users = response.getUsersList().map(u => ({
              userId: u.getUserId(),
              username: u.getUsername(),
              lastActive: u.getLastActive()
            }));
            setOnlineUsers(users);
          }
        });
      }
    }, 10000);

    return () => clearInterval(interval);
  }, [user]);

  const handleLogin = (userData) => {
    setUser(userData);
  };

  return (
    <ThemeProvider theme={theme}>
      <CssBaseline />
      <Box sx={{ height: '100vh', display: 'flex', flexDirection: 'column' }}>
        {!user ? (
          <LoginScreen onLogin={handleLogin} client={client} />
        ) : (
          <ChatScreen user={user} client={client} onlineUsers={onlineUsers} />
        )}
      </Box>
    </ThemeProvider>
  );
}

export default App;