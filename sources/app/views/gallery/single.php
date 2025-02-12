<?php
ob_start();

$photoCount = count($galleryPhotos);
// <?php echo __DIR__."/uploads/profiles/default.jpg";
?>
<main>
    <div class="gallery__title__header">
        <h1 class="left">
            <a href="/gallery"><-</a>
            <?php echo $title; ?>
        </h1>
        <div class="right">
            <div class="gallery__users">
                <?php foreach ($galleryUsers as $user) { ?>
                    <img src="
                <?php echo '/uploads/profiles/default.jpg'; ?>
                    " alt="<?php echo $user->username; ?>" title="<?php echo $user->username; ?>"
                        class="user__thumbnail">

                <?php } ?>
            </div>
            <a href="/gallery/upload/<?php echo $galleryId; ?>" class="button button-cta">Upload Photos</a>
            <a href="/gallery/addusers/<?php echo $galleryId; ?>" class="button button-cta">Add users+</a>
        </div>
    </div>

    
    <?php if ($photoCount == 0 || ($photoCount == 1 && empty($galleryPhotos[0]->id)) ) { ?>
        <div class="gallery__empty">
            <p>There are no photos in this gallery yet.</p>
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
