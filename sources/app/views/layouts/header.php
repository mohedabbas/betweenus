<?php
use App\Middlewares\AuthMiddleware;
$user = AuthMiddleware::getSessionUser();
$username = htmlspecialchars($user['username'] ?? 'Guest');
$userImg = $user['image_path'] ?? '/uploads/profiles/default.jpg';
?>

<header class="container">
    <!-- Logo/Brand section -->
    <a href="/" class="brand">
        <img src="../../../assets/images/brand-logo.png" alt="BetweenUs Logo">
        <span>BetweenUs</span>
    </a>

    <!-- Navigation buttons - only visible when NOT logged in -->
    <?php if (!isset($_SESSION['user'])): ?>
        <nav class="nav-buttons">
            <a class=" btn btn-primary" href="/register">Register</a>
            <a class="btn btn-primary" href="/login">Login</a>
        </nav>
    <?php endif; ?>

    <!-- User profile section and logout - only for logged in users -->
    <?php if (isset($_SESSION['user'])): ?>
        <div class="user-profile">
            <img src="<?= $userImg ?>" class="user__thumbnail" alt="Profile picture">
            <p><?php echo $username ?></p>
            <a class="btn btn-danger" href="/logout">Déconnexion</a>
        </div>
    <?php endif; ?>
</header>