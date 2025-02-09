<?php
ob_start();
$title = $title;
?>
<main>
	<section class="container hero-section">
		<h1>All your memories, <br>One place</h1>
		<p>Collect and share trip photos with your friends -- effortlessly</p>
		<a class="button" href="/login">Start uploading</a>
	</section>
</main>


<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
