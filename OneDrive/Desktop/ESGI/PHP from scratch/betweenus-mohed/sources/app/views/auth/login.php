<?php
ob_start();
$title = $title ?? 'Login';
?>
<h1>Connexion</h1>
<?php
if (isset($form)) {
    echo $form->renderForm();
}
$content = ob_get_clean();
require __DIR__ . '/../layouts/base.php';
