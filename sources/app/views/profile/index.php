<?php

$title = $data['title'];
ob_start();

$profile = $user->profile_image;

?>

<main class="container mt-5">
	<h1><?= $title ?></h1>
	<p>Enter your details to register.</p>
	<div class="container">
		<img src="<?php
        if ($profile == null) {
            echo 'uploads/profiles/default.jpg';
        } else {
            echo 'uploads/profiles/'.$profile;
        }
		?>" alt="profile image" class="profile-image">
	</div>
</main>

<?php

$content = ob_get_clean();
require __DIR__ . '/../layouts/base.php';
