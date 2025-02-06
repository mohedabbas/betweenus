<?php
ob_start();
?>


<style>
    .thumbnail__container {
        margin: 10px;
        border: 2px solid lightgray;
        width: 310px;
        height: 310px;
        display: flex;
        align-items: flex-start;
        border-radius: 10%;
        padding: 0.75rem;
    }

    .img__thumbnail {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 10%;
    }

    .gallery__container {
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }
</style>



<main>
    <h1><?php echo $title ?></h1>
    <div class="gallery__container">
        <?php
        foreach ($galleries as $gallery) {
            $galleryPhotos = json_decode($gallery->galleryPhotos);
            ?>
            <div class="gallery">
                <div class="thumbnail__container">
                    <?php foreach ($galleryPhotos as $photo) { ?>
                        <img src="/uploads/default/<?php echo $photo->image_path; ?>" alt="<?php echo $photo->caption; ?>"
                            title="<?php echo $photo->caption; ?>" class="img__thumbnail">
                    <?php } ?>
                </div>
                <h3><?php echo $gallery->gallery_name; ?></h3>
            </div>

        <?php } ?>
    </div>
    </div>






</main>



<?php

$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";