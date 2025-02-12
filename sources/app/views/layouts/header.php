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

    <?php if (isset($_SESSION['user'])): ?>
        <a href="/logout" class="user-profile">
            <img src="<?= $userImg ?>" class="user__thumbnail" />
            <?php echo $username ?>
        </a>
    <?php endif; ?>
</header>