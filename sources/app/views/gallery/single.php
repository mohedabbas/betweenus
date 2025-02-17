<?php
ob_start();

$photoCount = count($galleryPhotos);
// <?php echo __DIR__."/uploads/profiles/default.jpg";
?>
<main class="container">
    <!-- Gallery header -->
    <div class="flex flex--justify-between flex--wrap flex--gap-2 mb-3">
        <div class="flex flex--gap-2 flex--wrap">
            <a href="/gallery" class="button button--icon button--no-background">
                <img src="../../../assets/images/icons/arrow-left.png" alt="arrow-left">
            </a>
            <h1 class="m-0">
                <?php echo $title; ?>
            </h1>
        </div>
        <div class="flex flex--gap-2 flex--wrap">
            <div class="flex flex--align-center ml-2">
                <?php foreach ($galleryUsers as $user) { ?>
                    <img src="<?php echo '/uploads/profiles/default.jpg'; ?>" alt="<?php echo $user->username; ?>" title="<?php echo $user->username; ?>" class="user_thumbnail">
                <?php } ?>
            </div>
            <div class="flex flex--gap-2">
                <!-- Bouton d'upload remplaçant le lien -->
                <button id="uploadButton" type="button" class="button button-cta">
                    <img src="../../../assets/images/icons/picture.png" alt="picture icon">Ajouter
                </button>
                <a href="/gallery/addusers/<?php echo $galleryId; ?>" class="button button--secondary ">
                    <img src="../../../assets/images/icons/arrow-curved.png" alt="picture icon">
                    Inviter
                </a>
                <a href="/gallery/empty/<?php echo $galleryId; ?>" class="button button--secondary">
                    <img src="../../../assets/images/icons/delete.svg" alt="picture icon" style="width: 100%; height: 20px; object-fit: contain;">
                </a>
            </div>
        </div>
    </div>
    <!-- Gallery header -->

    <?php if ($photoCount == 0) { ?>
        <div class="">
            <p>Il n'y a pas de photo dans cette gallerie pour l'instant</p>
        </div>
    <?php } ?>

    <!-- Champ de fichier caché pour l'upload (utilisé par le JS externe) -->
    <input type="file" id="fileInput" name="files[]" style="display: none;" multiple data-gallery-id="<?= $galleryId ?>">

    <div class="grid">
        <?php foreach ($galleryPhotos as $photo) { ?>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="photo-card">
                    <img src="<?php echo $photo->image_path; ?>" alt="<?php echo $photo->caption; ?>" title="<?php echo $photo->caption; ?>" class="photo-card__img">
                    <button class="photo-card__zoom"></button>
                    <a href="/gallery/delete/<?php echo $photo->id ?>" class="photo-card__delete"></a>
                </div>
            </div>
        <?php } ?>
    </div>
</main>

<?php
$content = ob_get_clean();
require_once __DIR__ . '../../layouts/base.php';
