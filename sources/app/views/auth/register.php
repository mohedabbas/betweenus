<?php
ob_start();

// Le titre est passé par le contrôleur, sinon on met une valeur par défaut
$title = $title ?? 'Register';

// Récupération des erreurs, le formulaire, etc. 
// (ils sont passés par $data dans loadView)
$errors = $errors ?? [];
$form = $form ?? null;
?>

<div class="modal">
    <div class="form">
        <!-- Titre de la page -->
        <h1 class="form__title mb-2"><?= htmlspecialchars($title) ?></h1>

        <!-- Lien "déjà un compte ?" -->
        <p class="form__text mb-3">
            Vous avez déjà un compte ?
            <a href="/login">Se connecter</a>
        </p>

        <!-- Message de succès (inscription) -->
        <?php if (isset($_SESSION['register_info'])): ?>
            <div class="form__success-message">
                <?= htmlspecialchars($_SESSION['register_info']) ?>
            </div>
            <?php unset($_SESSION['register_info']); ?>
        <?php endif; ?>

        <!-- Affichage GLOBAL des erreurs s’il y en a -->
        <?php if (!empty($errors)): ?>
            <div class="form__error-message">
                <ul>
                    <?php foreach ($errors as $field => $message): ?>
                        <li><?= htmlspecialchars($message) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Rendu du formulaire -->
        <?php
        if ($form) {
            // Si votre classe Form gère déjà la valeur par défaut pour chaque champ,
            // elle affichera automatiquement ce qui a été injecté dans le contrôleur.
            echo $form->renderForm();
        }
        ?>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/base.php';
