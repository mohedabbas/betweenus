<?php
ob_start();
$title = $title ?? 'Login';
?>

<div class="login-container">
    <div class="login-card">
        <h1><?= htmlspecialchars($title) ?></h1>

        <p class="signup-text">
        Vous n'avez pas de compte ?
            <a href="/register">S'inscrire</a>
        </p>

        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="error-message">
                <?= htmlspecialchars($_SESSION['login_error']) ?>
                <?php unset($_SESSION['login_error']); ?>
            </div>
        <?php endif; ?>

        <form action="/login" method="POST">
            <div class="form-group">
                <label for="identifier">Email</label>
                <input 
                    type="email" 
                    id="identifier" 
                    name="identifier" 
                    required 
                    placeholder="Enter your email"
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    placeholder="Enter your password"
                >
            </div>

            <button type="submit" class="button">Login</button>
        </form>

        <p class="forgot-password-text">
            <a href="/forgot-password">Mot de passe oublié ?</a>
        </p>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/base.php';
?>