<?php
ob_start();
$title = $title ?? 'Réinitialisation';
?>

<div class="modal">
    <div class="form">
        <!-- En-tête de page -->
        <h1 class="mb-3"><?= htmlspecialchars($title) ?></h1>

        <!-- Afficher les messages d'erreur et de réussite dans des blocs de style -->
        <?php if (isset($_SESSION['reset_error'])): ?>
            <div class="error-message">
                <?= htmlspecialchars($_SESSION['reset_error']) ?>
            </div>
            <?php unset($_SESSION['reset_error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['reset_success'])): ?>
            <div class="success-message">
                <?= htmlspecialchars($_SESSION['reset_success']) ?>
            </div>
            <?php unset($_SESSION['reset_success']); ?>
        <?php endif; ?>

        <!-- Rendre le formulaire de réinitialisation (en supposant que $form est un objet avec la méthode renderForm() -->
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
