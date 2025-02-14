<?php
ob_start();
?>
<main>
    <div class="container">
        <div class="form-group m-auto">
            <h1><?php echo $title ?></h1>
            <?php echo $form->renderForm(); ?>
        </div>
    </div>
</main>

<?php

$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
