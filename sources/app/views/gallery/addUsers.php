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

        img {
            width: 2.5rem;
            height: 2.5rem;
            object-fit: cover;
            border-radius: 50%;
            position: relative;
        }

        a {
            margin-left: auto;

            img {
                width: 1.5rem;
                height: 1.5rem;
            }
        }
    }

    h3 {
        margin: 0;
    }

    h3 small {
        font-size: 0.8rem;
        color: var(--text-fade-color);
    }
</style>

<main class="container">
    <div class="flex flex--gap-2 flex--wrap">
        <a href="/gallery/<?php echo htmlspecialchars($galleryId); ?>"
            class="button button--icon button--no-background">
            <img src="/assets/images/icons/arrow-left.png" alt="Back">
        </a>
        <h1 class="m-0"><?php echo htmlspecialchars($title); ?></h1>
    </div>

    <?php echo $form->renderForm(); ?>

    <?php if ($countUser > 0) { ?>
        <div id="users-lists-to-add" class="container">
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
                                <img src="/assets/images/icons/<?php echo $isNewUser ? 'add-user.svg' : 'remove-user-2.svg'; ?>"
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
