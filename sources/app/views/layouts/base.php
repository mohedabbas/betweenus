<?php

use App\Utility\FlashMessage;
$title = $title ?? 'Default Title'; // Set a default title if none is provided
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>

<link rel="stylesheet" href="/assets/css/styles.css?v=<?= filemtime('assets/css/styles.css') ?>">
<link rel="stylesheet" href="/assets/css/temporary.css?v=<?= filemtime('assets/css/temporary.css') ?>">

</head>
<body>
<?php require __DIR__ . '/header.php'; ?>
<main class="container">
    <?php FlashMessage::display(); ?>
	<?= $content; // This will be where individual page content is injected ?>
</main>

<?php require __DIR__ . '/footer.php'; ?>

