<?php


ob_start();
?>

    <main>
        <?php
            echo $form->renderForm();
        ?>
    </main>



<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
