<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'gRPC Chat Application'; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header>
        <h1>gRPC Chat Application</h1>
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="/chat">Chat</a></li>
                    <li><a href="/profile">Profile</a></li>
                    <li><a href="/logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    
    <div class="container">
        <main>
            <?php echo $content ?? ''; ?>
        </main>
    </div>
    
    <footer>
        <p>&copy; 2025 gRPC Chat Application. All rights reserved.</p>
    </footer>
</body>
</html>