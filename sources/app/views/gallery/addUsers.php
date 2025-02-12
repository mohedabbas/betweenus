<?php
ob_start();
?>

<main>
    <h1><?php echo $title; ?></h1>
    <?php echo $form->renderForm(); ?>

    <div id="users-lists-to-add">
        <h2>User to add</h2>
        <ul>
            <?php foreach ($users as $user) { ?>
                <li>
                    <article style="display: flex; align-items: center;">
                        <img src="/uploads/profiles/default.jpg" alt="John Doe" class="user__thumbnail">
                        <h3><?php echo $user->first_name . " " . $user->last_name ?></h3>
                        <a href="/gallery/send_invite/<?php echo $user->id?>?galleryid=<?php echo $galleryId?>" class="button button-cta">+</a>
                    </article>
                </li>
            <?php } ?>
        </ul>
    </div>
</main>

<?php
$content = ob_get_clean();
require_once __DIR__ . '../../layouts/base.php';
