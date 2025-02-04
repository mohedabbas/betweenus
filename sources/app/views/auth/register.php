<?php
ob_start();
$title = $title ?? 'Register';

if (isset($_SESSION['register_info'])) {
    echo "<p style='color:green;'>" . $_SESSION['register_info'] . "</p>";
    unset($_SESSION['register_info']);
}
?>
<h1>Inscription</h1>

<?php
if (isset($form)) {
    echo $form->renderForm();
}
$content = ob_get_clean();
require __DIR__ . '/../layouts/base.php';
