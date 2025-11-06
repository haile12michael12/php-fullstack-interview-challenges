<?php $this->layout('layout', ['title' => 'Chat']); ?>

<h2>Chat Room</h2>

<div class="chat-container">
    <div class="chat-sidebar">
        <h3>Online Users</h3>
        <div class="user-list">
            <div class="user-list-item online">User 1</div>
            <div class="user-list-item online">User 2</div>
            <div class="user-list-item">User 3</div>
            <div class="user-list-item online">User 4</div>
        </div>
        
        <h3>Chat Rooms</h3>
        <div class="room-list">
            <div class="room-list-item active">General</div>
            <div class="room-list-item">Technology</div>
            <div class="room-list-item">Random</div>
        </div>
    </div>
    
    <div class="chat-main">
        <div class="chat-messages">
            <div class="chat-message">
                <strong>User 1:</strong> Hello everyone!
            </div>
            <div class="chat-message own">
                <strong>You:</strong> Hi there!
            </div>
            <div class="chat-message">
                <strong>User 2:</strong> Welcome to the chat!
            </div>
        </div>
        
        <div class="chat-input">
            <form id="message-form">
                <input type="text" id="message-input" placeholder="Type your message..." required>
                <button type="submit" class="btn">Send</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Simple chat functionality
    document.getElementById('message-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('message-input');
        if (input.value.trim() !== '') {
            // In a real implementation, you would send this via gRPC
            console.log('Sending message:', input.value);
            input.value = '';
        }
    });
</script>