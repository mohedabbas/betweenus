<?php
ob_start();
$title = $title;
?>
<main>
	<div class="container">
		<h1>Home</h1>
		<p>Welcome to the home page.</p>
	</div>
</main>


<?php
$content = ob_get_clean();
require __DIR__ ."/../layouts/base.php";
