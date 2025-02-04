<?php
ob_start();
$title = $title ?? 'Vérification';

if (isset($_SESSION['verify_error'])) {
    echo "<p style='color:red;'>" . $_SESSION['verify_error'] . "</p>";
    unset($_SESSION['verify_error']);
}
if (isset($_SESSION['verify_success'])) {
    echo "<p style='color:green;'>" . $_SESSION['verify_success'] . "</p>";
    unset($_SESSION['verify_success']);
}
?>
<h1>Vérification de compte</h1>

<?php
if (isset($form)) {
    echo $form->renderForm();
}
$content = ob_get_clean();
require __DIR__ . '/../layouts/base.php';
