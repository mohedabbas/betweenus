<?php
ob_start();
$title = $title ?? 'Login';
?>
<div class="modal">
    <form class="form" action="/login" method="POST">
        <h1 class="form__title mb-1"><?= htmlspecialchars($title) ?></h1>

        <p class="form__text mb-2 ">
            Vous n'avez pas de compte ?
            <a href="/register">S'inscrire</a>
        </p>

        <!-- Message d'erreur -->
        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="form__message">
                <?= htmlspecialchars($_SESSION['login_error']) ?>
                <?php unset($_SESSION['login_error']); ?>
            </div>
        <?php endif; ?>
        <!-- Message d'erreur -->
        <label for="identifier">Email</label>
        <input type="email" id="identifier" name="identifier" required class="mb-2">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required class="mb-3">
        <button type="submit" class="button form__button mb-2">Se connecter</button>

        <a class="forgot-password-text" href="/forgot-password">Mot de passe oublié ?</a>
    </form>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/base.php';
?>