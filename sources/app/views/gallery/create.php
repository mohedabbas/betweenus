<?php
ob_start();
?>
<main>
    <div class="modal">
        <div class="form">
            <h1 class="form__title mb-6"><?php echo $title ?></h1>
            <?php echo $form->renderForm(); ?>
        </div>
    </div>
</main>

<?php

$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
