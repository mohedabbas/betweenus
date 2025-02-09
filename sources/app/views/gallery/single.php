<?php
ob_start();
// <?php echo __DIR__."/uploads/profiles/default.jpg";
?>

<style>
    .gallery__container {
        display: flex;
        justify-content: flex-start;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .gallery__title__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .img__thumbnail {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid lightgray;
        position: relative;
        margin-left: -1.75rem;
    }

    .right {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>


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
                        class="img__thumbnail">

                <?php } ?>
            </div>
            <a href="/gallery/upload/<?php echo $galleryId; ?>" class="button button-cta">Upload Photos</a>
        </div>
    </div>
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
