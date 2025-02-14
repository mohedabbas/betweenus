<?php
ob_start();
?>
<main>
    <div class="container">
        <div class="flex flex--justify-between flex--wrap flex--gap-2 mb-3">
            <h1 class="m-0"><?php echo $title; ?></h1>
            <a href="/gallery/create" class="button button-cta">
                <img src="../../../assets/images/icons/plus.png" alt="plus icon"> Nouvelle Gallerie
            </a>
        </div>
        <div class="grid">
            <?php foreach ($galleries as $gallery) {
                $galleryPhotos = json_decode($gallery->galleryPhotos);
                ?>
                <div class="col-12 col-sm-6 col-lg-3">
                    <a href="/gallery/<?php echo $gallery->gallery_id; ?>" class="gallery">
                        <div class="gallery__thumbnail__container">
                            <?php if ((count($galleryPhotos) == 0 || (count($galleryPhotos) == 1 && empty($galleryPhotos[0]->id)))) {
                                $galleryPhotos = [];
                                echo '<p class="m-auto">Aucune photo</p>';
                            } else {
                                foreach ($galleryPhotos as $photo) { ?>
                                    <img src="<?php echo $photo->image_path; ?>" alt="<?php echo $photo->caption; ?>"
                                        title="<?php echo $photo->caption; ?>">
                                <?php }
                            } ?>
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
