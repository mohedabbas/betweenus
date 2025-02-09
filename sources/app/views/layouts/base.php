<?php

<<<<<<< HEAD
use App\Utility\FlashMessage;
=======
>>>>>>> 73e2919 (mise à jour 10.02.2025)
$title = $title ?? 'Default Title'; // Set a default title if none is provided
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="/assets/css/temporary.css">
</head>
<body>
<<<<<<< HEAD
<<<<<<< HEAD
<?php require __DIR__ . '/header.php'; ?>
<main>
    <?php FlashMessage::display(); ?>
	<?= $content; // This will be where individual page content is injected ?>
</main>
=======
<?php require __DIR__ . '/footer.php'; ?>
=======

<?php require __DIR__ . '/header.php'; ?>
<main>
    <?= $content; // This will be where individual page content is injected ?>
</main>

<?php require __DIR__ . '/footer.php'; ?>
<!--<script src="/assets/js/app.js"></script>-->
>>>>>>> 73e2919 (mise à jour 10.02.2025)
</body>
</html>