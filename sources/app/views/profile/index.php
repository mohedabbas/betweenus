<?php

$title = $data['title'];
ob_start();

if ($user['profile_image'] !== 'default.svg') {
	$profile = $user['profile_image'];
} else {
	$profile = null;
}

?>

<main>
	<div class="user-profile">
		<img src="<?= $profile ?? 'uploads/profiles/default.svg' ?>" alt="profile image" class="profile-image mb-3">
	</div>
	<h1 class="mb-3"><?= $title ?></h1>

	<h2 class="mb-2">Informations personnelles</h2>
	<ul>
		<li>
			<b>Pseudo</b> : <?= $user['username'] ?>
		</li>
		<li>
			<b>Email</b> : <?= $user['email'] ?>
		</li>
	</ul>
	<?php
	echo $form->renderForm();
	?>
</main>

<?php

$content = ob_get_clean();
require __DIR__ . '/../layouts/base.php';
