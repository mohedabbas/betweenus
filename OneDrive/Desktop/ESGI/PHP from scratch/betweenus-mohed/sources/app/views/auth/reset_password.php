<?php
ob_start();
$title = $title ?? 'Réinitialisation';

if (isset($_SESSION['reset_error'])) {
    echo "<p style='color:red;'>" . $_SESSION['reset_error'] . "</p>";
    unset($_SESSION['reset_error']);
}
if (isset($_SESSION['reset_success'])) {
    echo "<p style='color:green;'>" . $_SESSION['reset_success'] . "</p>";
    unset($_SESSION['reset_success']);
}
?>
<h1>Réinitialiser le mot de passe</h1>

<?php
if (isset($form)) {
    echo $form->renderForm();
}
$content = ob_get_clean();
require __DIR__ . '/../layouts/base.php';
