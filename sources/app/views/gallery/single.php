<?php
ob_start();

$photoCount = count($galleryPhotos);
// <?php echo __DIR__."/uploads/profiles/default.jpg";
?>
<main>
    <div class="flex flex--justify-between flex--wrap">
        <div class="flex flex--gap-2 flex--wrap">
            <a href="/gallery" class="button button--icon button--secondary">
                <img src="../../../assets/images/icons/arrow-left.png" alt="arrow-left">
            </a>
            <h1 class="m-0">
                <?php echo $title; ?>
            </h1>
        </div>
        <div class="flex flex--gap-2 flex--wrap">
            <div class="gallery__users flex flex--align-center">
                <?php foreach ($galleryUsers as $user) { ?>
                    <img src="
                <?php echo '/uploads/profiles/default.jpg'; ?>
                    " alt="<?php echo $user->username; ?>" title="<?php echo $user->username; ?>"
                        class="user__thumbnail">

                <?php } ?>
            </div>
            <div class="flex flex--gap-2">
                <a href="/gallery/upload/<?php echo $galleryId; ?>" class="button button-cta">
                    <img src="../../../assets/images/icons/picture.png" alt="picture icon">Ajouter des photos</a>
                <a href="/gallery/addusers/<?php echo $galleryId; ?>" class="button button--secondary ">
                    <img src="../../../assets/images/icons/arrow-curved.png" alt="picture icon">
                    Inviter</a>
            </div>
        </div>
    </div>


    <?php if ($photoCount == 0) { ?>
        <div class="gallery__empty">
            <p>Il n'y a pas de photo dans cette gallerie pour l'instant</p>
        </div>
    <?php } ?>

    <div class="gallery__container">
        <?php foreach ($galleryPhotos as $photo) { ?>
            <div class="photo-card">
                <img src="<?php echo $photo->image_path; ?>" alt="<?php echo $photo->caption; ?>"
                    title="<?php echo $photo->caption; ?>" class="photo-card__img">
                <button class="photo-card__zoom"></button>
                <a href="/gallery/delete/<?php echo $photo->id ?>" class="photo-card__delete"></a>
            </div>
        <?php } ?>
    </div>
</main>


<?php
$content = ob_get_clean();
require_once __DIR__ . '../../layouts/base.php';
