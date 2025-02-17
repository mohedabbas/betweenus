<?php
ob_start();
$countUser = count($users);
?>

<style>
    ul {
        list-style: none;
        background-color: var(--light-grey-color);
        border-radius: 20px;
        padding: 1rem;
    }

    small {
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
        <div class="m-6">
            <h1 class="mb-3">Membres</h1>
            <ul>
                <?php foreach ($users as $user) {
                    $isNewUser = isset($user->is_newUser) && $user->is_newUser;
                    $fullName = htmlspecialchars($user->first_name ?? '') . ' ' . htmlspecialchars($user->last_name ?? '');
                    ?>
                    <li>
                        <article class="flex flex--justify-between flex--align-center mb-2">
                            <div class="user-profile">
                                <img src="/uploads/profiles/default.svg" alt="<?php echo $fullName; ?>">
                                <p>
                                    <b><?php echo $fullName; ?></b>
                                    <br>
                                    <small class="small-text"><?php echo htmlspecialchars($user->email); ?></small>
                                </p>
                            </div>

                            <a href="/gallery/<?php echo $isNewUser ? 'send_invite' : 'removeuser'; ?>/<?php echo htmlspecialchars($user->id); ?>?galleryid=<?php echo htmlspecialchars($galleryId); ?>"
                                class="button button--cta">
                                <img src="/assets/images/icons/<?php echo $isNewUser ? 'add-user.png' : 'remove-user.png'; ?>"
                                    alt="<?php echo $isNewUser ? 'Invite user' : 'Remove user'; ?>" />
                                <?php echo $isNewUser ? 'Inviter' : 'Supprimer'; ?>
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
