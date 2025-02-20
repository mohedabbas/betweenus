<?php
ob_start();
$title = $title ?? 'Vérification';
?>

<div class="modal">
    <div class="form">
        <!-- En-tête de page -->
        <h1 class="mb-3 form__title"><?= htmlspecialchars($title) ?></h1>

        <!-- Afficher les messages d'erreur et de réussite dans des blocs de style -->
        <?php if (isset($_SESSION['verify_error'])): ?>
            <div class="form__message">
                <?= htmlspecialchars($_SESSION['verify_error']) ?>
            </div>
            <?php unset($_SESSION['verify_error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['verify_success'])): ?>
            <div class="form__message form__message--success">
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
