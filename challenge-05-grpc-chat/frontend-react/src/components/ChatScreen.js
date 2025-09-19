import React, { useState, useEffect, useRef } from 'react';
import { 
  Box, 
  Paper, 
  Typography, 
  TextField, 
  Button, 
  List, 
  ListItem,
  ListItemText,
  Divider,
  AppBar,
  Toolbar,
  IconButton,
  Badge,
  Drawer,
  Avatar,
  ListItemAvatar
} from '@mui/material';
import { Send as SendIcon, People as PeopleIcon, ExitToApp as LogoutIcon } from '@mui/icons-material';
import { JoinRequest, ChatMessage, HistoryRequest } from '../generated/chat_pb';

const ChatScreen = ({ user, client, onlineUsers }) => {
  const [messages, setMessages] = useState([]);
  const [newMessage, setNewMessage] = useState('');
  const [drawerOpen, setDrawerOpen] = useState(false);
  const messagesEndRef = useRef(null);
  const [stream, setStream] = useState(null);

  // Scroll to bottom when messages change
  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  }, [messages]);

  // Join chat and set up message stream
  useEffect(() => {
    if (!client || !user) return;

    // Create join request
    const request = new JoinRequest();
    request.setUserId(user.userId);
    request.setUsername(user.username);

    // Join chat and get message stream
    const stream = client.joinChat(request);
    setStream(stream);

    // Handle incoming messages
    stream.on('data', (response) => {
      const message = {
        id: response.getId(),
        userId: response.getUserId(),
        username: response.getUsername(),
        content: response.getContent(),
        timestamp: response.getTimestamp(),
        type: response.getType()
      };
      
      setMessages(prev => [...prev, message]);
    });

    stream.on('error', (err) => {
      console.error('Stream error:', err);
    });

    // Load chat history
    const historyRequest = new HistoryRequest();
    historyRequest.setLimit(20);
    client.getChatHistory(historyRequest, {}, (err, response) => {
      if (!err) {
        const historyMessages = response.getMessagesList().map(msg => ({
          id: msg.getId(),
          userId: msg.getUserId(),
          username: msg.getUsername(),
          content: msg.getContent(),
          timestamp: msg.getTimestamp(),
          type: msg.getType()
        }));
        setMessages(historyMessages.reverse());
      }
    });

    return () => {
      if (stream) {
        stream.cancel();
      }
    };
  }, [client, user]);

  const handleSendMessage = (e) => {
    e.preventDefault();
    
    if (!newMessage.trim()) return;
    
    const message = new ChatMessage();
    message.setUserId(user.userId);
    message.setUsername(user.username);
    message.setContent(newMessage);
    message.setTimestamp(Math.floor(Date.now() / 1000));
    message.setType(0); // TEXT type
    
    client.sendMessage(message, {}, (err, response) => {
      if (err) {
        console.error('Error sending message:', err);
      }
    });
    
    setNewMessage('');
  };

  const formatTime = (timestamp) => {
    const date = new Date(timestamp * 1000);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  };

  const renderMessage = (message) => {
    const isCurrentUser = message.userId === user.userId;
    const messageType = parseInt(message.type);
    
    // System, join, or leave message
    if (messageType > 0) {
      return (
        <Box 
          sx={{ 
            textAlign: 'center', 
            my: 1, 
            color: 'text.secondary',
            fontSize: '0.85rem'
          }}
        >
          {message.content}
        </Box>
      );
    }
    
    return (
      <Box
        sx={{
          display: 'flex',
          justifyContent: isCurrentUser ? 'flex-end' : 'flex-start',
          mb: 2
        }}
      >
        <Paper
          elevation={1}
          sx={{
            p: 2,
            maxWidth: '70%',
            backgroundColor: isCurrentUser ? 'primary.light' : 'grey.100',
            color: isCurrentUser ? 'white' : 'text.primary',
            borderRadius: 2
          }}
        >
          {!isCurrentUser && (
            <Typography variant="subtitle2" sx={{ fontWeight: 'bold' }}>
              {message.username}
            </Typography>
          )}
          <Typography variant="body1">{message.content}</Typography>
          <Typography variant="caption" sx={{ display: 'block', textAlign: 'right', mt: 0.5 }}>
            {formatTime(message.timestamp)}
          </Typography>
        </Paper>
      </Box>
    );
  };

  return (
    <>
      <AppBar position="static">
        <Toolbar>
          <Typography variant="h6" component="div" sx={{ flexGrow: 1 }}>
            gRPC Chat
          </Typography>
          <IconButton 
            color="inherit" 
            onClick={() => setDrawerOpen(true)}
          >
            <Badge badgeContent={onlineUsers.length} color="secondary">
              <PeopleIcon />
            </Badge>
          </IconButton>
          <IconButton color="inherit">
            <LogoutIcon />
          </IconButton>
        </Toolbar>
      </AppBar>
      
      <Box sx={{ flexGrow: 1, overflow: 'auto', p: 2 }}>
        {messages.map((message) => (
          <Box key={message.id}>
            {renderMessage(message)}
          </Box>
        ))}
        <div ref={messagesEndRef} />
      </Box>
      
      <Box 
        component="form" 
        onSubmit={handleSendMessage}
        sx={{ 
          p: 2, 
          backgroundColor: 'background.paper',
          borderTop: 1,
          borderColor: 'divider',
          display: 'flex'
        }}
      >
        <TextField
          fullWidth
          placeholder="Type a message"
          variant="outlined"
          value={newMessage}
          onChange={(e) => setNewMessage(e.target.value)}
          sx={{ mr: 1 }}
        />
        <Button 
          variant="contained" 
          color="primary" 
          type="submit"
          endIcon={<SendIcon />}
        >
          Send
        </Button>
      </Box>
      
      <Drawer
        anchor="right"
        open={drawerOpen}
        onClose={() => setDrawerOpen(false)}
      >
        <Box sx={{ width: 250, p: 2 }}>
          <Typography variant="h6" sx={{ mb: 2 }}>
            Online Users ({onlineUsers.length})
          </Typography>
          <Divider />
          <List>
            {onlineUsers.map((onlineUser) => (
              <ListItem key={onlineUser.userId}>
                <ListItemAvatar>
                  <Avatar sx={{ bgcolor: onlineUser.userId === user.userId ? 'primary.main' : 'secondary.main' }}>
                    {onlineUser.username.charAt(0).toUpperCase()}
                  </Avatar>
                </ListItemAvatar>
                <ListItemText 
                  primary={onlineUser.username} 
                  secondary={onlineUser.userId === user.userId ? '(You)' : ''}
                />
              </ListItem>
            ))}
          </List>
        </Box>
      </Drawer>
    </>
  );
};

export default ChatScreen;