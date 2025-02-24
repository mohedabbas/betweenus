<?php
use App\Middlewares\AuthMiddleware;
$user = AuthMiddleware::getSessionUser();
$username = htmlspecialchars($user['username'] ?? 'Guest');
$userImg = $user['image_path'] ?? '/uploads/profiles/default.svg';
?>

<header class="container">
    <!-- Logo/Brand section -->
    <a href="/" class="brand">
        <img src="/assets/images/brand-logo.png" alt="BetweenUs Logo">
        <span>BetweenUs</span>
    </a>

    <?php if (isset($_SESSION['user'])): ?>
        <div class="user-menu">
            <a href="/profile" class="user-profile">
                <img src="<?= $userImg ?>" class="user__thumbnail" />
                <span><?php echo $username ?></span>
            </a>
            <div class="user-dropdown">
                <a href="/logout" class="dropdown-item">Déconnexion</a>
            </div>
        </div>
    <?php endif; ?>
</header>