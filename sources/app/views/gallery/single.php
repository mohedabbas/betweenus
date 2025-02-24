<?php
ob_start();
$photoCount = count($galleryPhotos);
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
                <?php foreach (array_slice($galleryUsers, 0, 4) as $user) { ?>
                    <img src="<?= $user->profile_image == 'default.png' ? '/uploads/profiles/default.svg' : $user->profile_image ?>"
                        alt="<?php echo $user->username; ?>" title="<?php echo $user->username; ?>" class="user_thumbnail">
                <?php } ?>
            </div>
            <div class="flex flex--gap-2 flex--align-center">
                <a href="/gallery/addusers/<?php echo $galleryId; ?>" class="button button--secondary ">
                    <img src="/assets/images/icons/arrow-curved.png" alt="picture icon">
                    Inviter
                </a>

                <!-- Dropdown Menu -->
                <div class="user-menu">
                    <button class="button button--icon button--secondary">
                        <img src="/assets/images/icons/dropdown.svg" alt="picture icon">
                    </button>
                    <div class="user-dropdown">
                        <a href="/gallery/empty/<?php echo $galleryId; ?>" class="dropdown-item">
                            <img src="/assets/images/icons/delete.svg" alt="picture icon">
                            Vider la galerie
                        </a>
                        <a href="/gallery/deletegallery/<?php echo $galleryId; ?>" class="dropdown-item">
                            <img src="/assets/images/icons/delete-gallery.svg" alt="picture icon">
                            Supprimer la galerie
                        </a>
                    </div>
                </div>

                <!-- Bouton d'upload remplaçant le lien -->
                <button id="uploadButton" type="button" class="button button-cta">
                    <img src="../../../assets/images/icons/picture.png" alt="picture icon">Ajouter
                </button>
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
    <input type="file" id="fileInput" name="files[]" style="display: none;" multiple
        data-gallery-id="<?= $galleryId ?>">

    <div class="grid">
        <?php foreach ($galleryPhotos as $photo) { ?>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="photo-card">
                    <img src="<?php echo $photo->image_path; ?>" alt="<?php echo $photo->caption; ?>"
                        title="<?php echo $photo->caption; ?>" class="photo-card__img">
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
