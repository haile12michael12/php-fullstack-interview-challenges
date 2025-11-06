<?php $this->layout('layout', ['title' => 'Login']); ?>

<h2>Login to Your Account</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<form method="POST" action="/login">
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    
    <button type="submit" class="btn">Login</button>
</form>

<p>Don't have an account? <a href="/register">Register here</a></p>