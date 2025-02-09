<?php
ob_start();
?>



<main>
    <h1><?php echo $title; ?></h1>
    <?php echo $form->renderForm(); ?>

    <div id="users-lists-to-add">
        <h2>User to add</h2>
        <ul>
            <li>
                <article style="display: flex; align-items: center;">
                    <img src="/uploads/profiles/default.jpg" alt="John Doe" class="user__thumbnail">
                    <h3>John Doe</h3>
                    <button class="button button-cta">+</button>
                </article>
            </li>
            <li>
                <article style="display: flex; align-items: center;">
                    <img src="/uploads/profiles/default.jpg" alt="John Doe" class="user__thumbnail">
                    <h3>John Doe</h3>
                    <button class="button button-cta">+</button>
                </article>
            </li>
        </ul>
    </div>
</main>

<?php
$content = ob_get_clean();
require_once __DIR__ . '../../layouts/base.php';
