<?php
ob_start();
?>

<style>
    .gallery__title__header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .thumbnail__container {
        margin: 10px;
        border: 2px solid lightgray;
        width: 310px;
        height: 310px;
        display: flex;
        align-items: flex-start;
        border-radius: 10%;
        padding: 0.75rem;
        flex-wrap: wrap;
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

    .gallery {
        margin: 10px;
        text-align: center;
        text-decoration: none;
        color: black;
    }
</style>

<main>

    <div class="gallery__page__header">
        <div class="gallery__title__header">
            <h1 class="left"><?php echo $title; ?></h1>
            <a href="/gallery/create" class="button button-cta">Create Gallery</a>
        </div>
    </div>
    <div class="gallery__container">
        <?php
        foreach ($galleries as $gallery) {
            $galleryPhotos = json_decode($gallery->galleryPhotos);
            ?>
            <a href="/gallery/<?php echo $gallery->gallery_id; ?>" class="gallery">
                <div class="thumbnail__container">
                    <?php foreach ($galleryPhotos as $photo) { ?>
                        <img src="<?php echo $photo->image_path; ?>" alt="<?php echo $photo->caption; ?>"
                            title="<?php echo $photo->caption; ?>" class="img__thumbnail">
                    <?php } ?>
                </div>
                <h3><?php echo $gallery->gallery_name; ?></h3>
            </a>

        <?php } ?>
    </div>
    </div>
</main>
<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
