<?php
ob_start();
$title = $title ?? 'Mot de passe oublié';

if (isset($_SESSION['forgot_error'])) {
    echo "<p style='color:red;'>" . $_SESSION['forgot_error'] . "</p>";
    unset($_SESSION['forgot_error']);
}
if (isset($_SESSION['forgot_info'])) {
    echo "<p style='color:green;'>" . $_SESSION['forgot_info'] . "</p>";
    unset($_SESSION['forgot_info']);
}
?>
<h1>Mot de passe oublié</h1>
<p>Veuillez entrer votre email pour recevoir un lien de réinitialisation.</p>

<?php
if (isset($form)) {
    echo $form->renderForm();
}
$content = ob_get_clean();
require __DIR__ . '/../layouts/base.php';
