<?php
ob_start();
?>
<style>
    .gallery__title__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
<main>
    <div class="container">
        <div class="gallery__page__header">
            <div class="gallery__title__header">
                <h1 class="left"><?php echo $title; ?></h1>
                <a href="/gallery/create" class="button button-cta">Nouvelle Gallerie
                    <img src="../../../assets/images/icons/plus.png" alt="">
                </a>
            </div>
        </div>
        <div class="grid">
            <?php
            foreach ($galleries as $gallery) {
                $galleryPhotos = json_decode($gallery->galleryPhotos);
                ?>
                <div class="col-4 col-md-2 col-lg-1">
                    <a href="/gallery/<?php echo $gallery->gallery_id; ?>" class="gallery">
                        <div class="gallery__thumbnail__container">
                            <?php foreach ($galleryPhotos as $photo) { ?>
                                <img src="<?php echo $photo->image_path; ?>" alt="<?php echo $photo->caption; ?>"
                                    title="<?php echo $photo->caption; ?>">
                            <?php } ?>
                        </div>
                        <h3 class="gallery__name"><?php echo $gallery->gallery_name; ?></h3>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</main>
<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
