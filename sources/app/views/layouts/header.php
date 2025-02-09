<?php
use App\Middlewares\AuthMiddleware;
$user = AuthMiddleware::getSessionUser();
$username = htmlspecialchars($user['username'] ?? 'Guest');
$userImg = $user['image_path'] ?? '/uploads/profiles/default.jpg';
?>


<header>
    <nav>
        <ul>
            <li><a href="/">Home</a></li>
            <?php if (isset($_SESSION['user'])): ?>
                <li><a href="/gallery">Gallery</a></li>
                <li><a href="/logout">Logout</a></li>
                <li><img src="<?= $userImg ?>" class="user__thumbnail"/> <?php echo $username ?></li>
            <?php else: ?>
                <li><a href="/login">Login</a></li>
                <li><a href="/register">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
