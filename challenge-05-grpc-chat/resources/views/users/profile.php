<?php $this->layout('layout', ['title' => 'User Profile']); ?>

<h2>User Profile</h2>

<?php if (isset($user)): ?>
    <div class="user-profile">
        <div class="form-group">
            <label>Username:</label>
            <p><?php echo htmlspecialchars($user['username']); ?></p>
        </div>
        
        <div class="form-group">
            <label>Email:</label>
            <p><?php echo htmlspecialchars($user['email']); ?></p>
        </div>
        
        <div class="form-group">
            <label>Member Since:</label>
            <p><?php echo htmlspecialchars($user['created_at']); ?></p>
        </div>
        
        <div class="form-group">
            <label>Last Seen:</label>
            <p><?php echo htmlspecialchars($user['last_seen'] ?? 'Recently'); ?></p>
        </div>
        
        <div class="form-group">
            <label>Status:</label>
            <p><?php echo $user['is_online'] ? 'Online' : 'Offline'; ?></p>
        </div>
        
        <a href="/edit-profile" class="btn">Edit Profile</a>
    </div>
<?php else: ?>
    <p>User not found.</p>
<?php endif; ?>