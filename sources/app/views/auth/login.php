<?php
ob_start();
$title = $title ?? 'Connexion';

// Afficher l’erreur s’il y a
if (isset($_SESSION['login_error'])) {
    echo "<p style='color:red;'>" . $_SESSION['login_error'] . "</p>";
    unset($_SESSION['login_error']);
}
?>
<h1><?= htmlspecialchars($title) ?></h1>

<?php
// Afficher le formulaire de connexion
if (isset($form)) {
    echo $form->renderForm();
}
?>

<!-- Lien vers la page "Mot de passe oublié" -->
<p>
    <a href="/forgot-password">Mot de passe oublié ?</a>
</p>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/base.php';
