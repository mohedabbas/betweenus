<?php
ob_start();
$title = $title ?? 'Vérification';
?>

<div class="login-container">
    <div class="login-card form-group">
        <!-- En-tête de page -->
        <h1><?= htmlspecialchars($title) ?></h1>

        <!-- Afficher les messages d'erreur et de réussite dans des blocs de style -->
        <?php if (isset($_SESSION['verify_error'])): ?>
            <div class="error-message">
                <?= htmlspecialchars($_SESSION['verify_error']) ?>
            </div>
            <?php unset($_SESSION['verify_error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['verify_success'])): ?>
            <div class="success-message">
                <?= htmlspecialchars($_SESSION['verify_success']) ?>
            </div>
            <?php unset($_SESSION['verify_success']); ?>
        <?php endif; ?>

        <!-- Rendre le formulaire (en supposant que $form est un objet avec une méthode renderForm()) -->
        <?php
        if (isset($form)) {
            echo $form->renderForm();
        }
        ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/base.php';
