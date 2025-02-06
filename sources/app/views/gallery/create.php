<?php
ob_start();
?>
<main>
    <h1><?php echo $title ?></h1>
    <div>
        <?php echo $form->renderForm(); ?>
    </div>
</main>

<?php

$content = ob_get_clean();
require __DIR__ ."/../layouts/base.php";
