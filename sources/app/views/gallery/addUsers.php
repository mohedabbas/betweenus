<?php
ob_start();
$countUser = count($users);
?>

<style>
    #users-lists-to-add {
        margin-top: 2rem;
    }

    ul {
        list-style: none;
        padding: 0;
    }

    article {
        display: flex;
        align-items: center;
        border: 1px solid #ccc;
        justify-content: flex-start;
        gap: 1rem;
        padding: 1rem;

        /* img {
            width: 2.5rem;
            height: 2.5rem;
            object-fit: cover;
            border-radius: 50%;
            position: relative;
        } */
    }

    h3 small {
        font-size: 0.8rem;
        color: var(--text-fade-color);
    }
</style>

<main>
    <!-- Gallery header -->
    <div class="flex flex--justify-between flex--wrap flex--gap-2 mb-3">
        <div class="flex flex--gap-2 flex--wrap">
            <a href="/gallery" class="button button--icon button--no-background">
                <img src="../../../assets/images/icons/arrow-left.png" alt="arrow-left">
            </a>
            <h1 class="m-0">
                <?php echo htmlspecialchars($title); ?>
            </h1>
        </div>
    </div>

    <div class="form form--inline">
        <?php echo $form->renderForm(); ?>
    </div>

    <?php if ($countUser > 0) { ?>
        <div id="users-lists-to-add">
            <h1 class="m-0">Les Membres</h1>
            <ul>
                <?php foreach ($users as $user) {
                    $isNewUser = isset($user->is_newUser) && $user->is_newUser;
                    $fullName = htmlspecialchars($user->first_name ?? '') . ' ' . htmlspecialchars($user->last_name ?? '');
                    ?>
                    <li>
                        <article>
                            <img src="/uploads/profiles/default.jpg" alt="<?php echo $fullName; ?>">
                            <h3>
                                <?php echo $fullName; ?>
                                <br>
                                <small class="small-text"><?php echo htmlspecialchars($user->email); ?></small>
                            </h3>

                            <a href="/gallery/<?php echo $isNewUser ? 'send_invite' : 'removeuser'; ?>/<?php echo htmlspecialchars($user->id); ?>?galleryid=<?php echo htmlspecialchars($galleryId); ?>"
                                class="button button--cta">
                                <img src="/assets/images/icons/<?php echo $isNewUser ? 'add-user.png' : 'remove-user.png'; ?>"
                                    alt="<?php echo $isNewUser ? 'Invite user' : 'Remove user'; ?>" />
                                <?php echo $isNewUser ? 'Invite' : ''; ?>
                            </a>
                        </article>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
</main>

<?php
$content = ob_get_clean();
require_once __DIR__ . '../../layouts/base.php';
