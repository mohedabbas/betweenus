<?php
ob_start();
$title = $title;
?>
<main>
	<section class="container hero-section">
		<h1>Tous vos souvenirs, <br> un seul endroit <br></h1>
		<p>Partagez facilement vos photos de voyage entre amis.</p>
		<a class="button" href="/login">Partagez des photos</a>
	</section>
</main>


<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
