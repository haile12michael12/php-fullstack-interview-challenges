<?php $this->layout('layout', ['title' => 'Home']); ?>

<h2>Welcome to gRPC Chat</h2>

<div class="welcome-message">
    <p>This is a real-time chat application built with gRPC technology.</p>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>You are logged in. <a href="/chat" class="btn">Start Chatting</a></p>
    <?php else: ?>
        <p>Please <a href="/login">login</a> or <a href="/register">register</a> to start chatting.</p>
    <?php endif; ?>
</div>

<div class="features">
    <h3>Features</h3>
    <ul>
        <li>Real-time messaging with gRPC</li>
        <li>Secure authentication</li>
        <li>Chat rooms and private messaging</li>
        <li>User presence indicators</li>
        <li>Message history</li>
    </ul>
</div>