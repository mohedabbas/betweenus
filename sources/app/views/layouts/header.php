
<?php
use App\Middlewares\AuthMiddleware;
$user = AuthMiddleware::getSessionUser();
$username = htmlspecialchars($user['username'] ?? 'Guest');
$userImg = $user['image_path'] ?? '/uploads/profiles/default.jpg';
?>


<header class="container">
    <a href="/" class="brand">
        <img src="../../../assets/images/brand-logo.png" alt="">
        BetweenUs
    </a>

    <?php if (isset($_SESSION['user'])): ?>
        <div class="user-profile">
            <img src="<?= $userImg ?>" class="user__thumbnail" />
            <p>
                <?php echo $username ?>
            </p>
        </div>
    <?php endif; ?>
</header>