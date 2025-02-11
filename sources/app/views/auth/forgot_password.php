<?php
ob_start();
$title = $title ?? 'Mot de passe oublié';
?>

<div class="login-container">
    <div class="login-card form-group">
        <!-- En-tête de page-->
        <h1><?= htmlspecialchars($title) ?></h1>

        <!-- Afficher les messages d'erreur et d'information dans des blocs de style -->
        <?php if (isset($_SESSION['forgot_error'])): ?>
            <div class="error-message">
                <?= htmlspecialchars($_SESSION['forgot_error']) ?>
            </div>
            <?php unset($_SESSION['forgot_error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['forgot_info'])): ?>
            <div class="success-message">
                <?= htmlspecialchars($_SESSION['forgot_info']) ?>
            </div>
            <?php unset($_SESSION['forgot_info']); ?>
        <?php endif; ?>

        <!-- Texte d'instruction -->
        <p>Veuillez entrer votre email pour recevoir un lien de réinitialisation.</p>

        <!-- Rendre le formulaire (en supposant que $form est un objet avec une méthode renderForm()) -->
        <?php
        if (isset($form)) {
            echo $form->renderForm();
        }
        ?>

        <!-- Lien facultatif vers la page de connexion ou d'accueil -->
        <p class="signup-text">
            Vous avez déjà un compte ?
            <a href="/login">Connectez-vous</a>
        </p>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/base.php';
