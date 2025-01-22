<?php
ob_start();
$title = $title;
$form = $data['form'];
?>
<main>
	<div class="container">
		<h1><?php echo $title; ?></h1>
		<p>Welcome to the Register page.</p>
		<div class="container">
			<?php echo $form->renderForm(); ?>
		</div>
	</div>
</main>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/base.php';
