<?php
ob_start();
?>
<main>
    <div class="form-group m-auto">
        <h1><?php echo $title ?></h1>
        <?php echo $form->renderForm(); ?>
    </div>
</main>

<?php

$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
