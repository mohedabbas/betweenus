<<<<<<< HEAD
<?php
use App\Middlewares\AuthMiddleware;
$user = AuthMiddleware::getSessionUser();
$username = htmlspecialchars($user['username'] ?? 'Guest');
$userImg = $user['image_path'] ?? '/uploads/profiles/default.jpg';
?>

=======

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
>>>>>>> 73e2919 (mise à jour 10.02.2025)

<header>
    <nav class="navbar">
        <ul class="navbar-links" >
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

<link rel="stylesheet" href="/assets/css/styles.css">