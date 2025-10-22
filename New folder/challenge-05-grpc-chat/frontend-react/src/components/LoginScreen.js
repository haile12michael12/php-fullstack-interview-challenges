import React, { useState } from 'react';
import { 
  Box, 
  Button, 
  TextField, 
  Typography, 
  Paper, 
  Container,
  Alert
} from '@mui/material';
import { v4 as uuidv4 } from 'uuid';

const LoginScreen = ({ onLogin, client }) => {
  const [username, setUsername] = useState('');
  const [error, setError] = useState('');
  const [isLoading, setIsLoading] = useState(false);

  const handleSubmit = (e) => {
    e.preventDefault();
    
    if (!username.trim()) {
      setError('Username is required');
      return;
    }

    setIsLoading(true);
    
    // Generate a unique user ID
    const userId = uuidv4();
    
    // Create user object
    const user = {
      userId,
      username: username.trim()
    };
    
    // Simulate checking connection to server
    setTimeout(() => {
      setIsLoading(false);
      onLogin(user);
    }, 500);
  };

  return (
    <Container maxWidth="sm" sx={{ height: '100vh', display: 'flex', alignItems: 'center' }}>
      <Paper 
        elevation={3} 
        sx={{ 
          p: 4, 
          width: '100%',
          borderRadius: 2,
          backgroundColor: 'white'
        }}
      >
        <Typography variant="h4" component="h1" gutterBottom align="center">
          gRPC Chat
        </Typography>
        
        <Typography variant="body1" gutterBottom align="center" sx={{ mb: 3 }}>
          Enter a username to join the chat
        </Typography>
        
        {error && (
          <Alert severity="error" sx={{ mb: 2 }}>
            {error}
          </Alert>
        )}
        
        <Box component="form" onSubmit={handleSubmit} noValidate>
          <TextField
            margin="normal"
            required
            fullWidth
            id="username"
            label="Username"
            name="username"
            autoComplete="username"
            autoFocus
            value={username}
            onChange={(e) => setUsername(e.target.value)}
            disabled={isLoading}
          />
          
          <Button
            type="submit"
            fullWidth
            variant="contained"
            sx={{ mt: 3, mb: 2, py: 1.5 }}
            disabled={isLoading}
          >
            {isLoading ? 'Connecting...' : 'Join Chat'}
          </Button>
        </Box>
      </Paper>
    </Container>
  );
};

export default LoginScreen;