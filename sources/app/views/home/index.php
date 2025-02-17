<?php
ob_start();
$title = $title;
?>
<main>
	<section class="hero-section">
		<img src="/assets/images/brand-logo-shadow.png" alt="">
		<h1>Tous vos souvenirs, <br> un seul endroit <br></h1>
		<p>Partagez facilement vos photos de voyage entre amis.</p>
		<a class="button" href="/login">Partagez des photos</a>
	</section>
	<section>
		<h1 class="text-center mb-4">Comment ça marche ?</h1>
		<div class="grid">
			<div class="col-12 col-sm-6 col-md-4"><img src="/assets/images/step-1.png" alt="step1" class="w-100"></div>
			<div class="col-12 col-sm-6 col-md-4"><img src="/assets/images/step-2.png" alt="step2" class="w-100"></div>
			<div class="col-12 col-sm-6 col-md-4"><img src="/assets/images/step-3.png" alt="step3" class="w-100"></div>
		</div>
	</section>
</main>


<?php
$content = ob_get_clean();
require __DIR__ . "/../layouts/base.php";
