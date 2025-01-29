<?php
ob_start();
$title = $title ?? 'Connecté';
?>
<h1>Zone connectée</h1>

<!-- Message de succès -->
<?php if (isset($_SESSION['login_success'])): ?>
    <p style="color: green;">
        <?= htmlspecialchars($_SESSION['login_success']) ?>
    </p>
    <?php unset($_SESSION['login_success']); ?>
<?php endif; ?>

<p>Bienvenue, vous êtes connecté(e) en tant que 
   <?= htmlspecialchars($_SESSION['user']['first_name'] ?? '') ?>
   <?= htmlspecialchars($_SESSION['user']['last_name'] ?? '') ?>
   (<?= htmlspecialchars($_SESSION['user']['username'] ?? '') ?>) !
</p>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/base.php';
