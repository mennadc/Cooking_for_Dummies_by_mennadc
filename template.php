<?php
if (!defined('CONST_INCLUDE'))
	die('Direct access prohibited !');
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<title>Cooking for Dummies</title>

	<!-- Jquery JS -->
	<link rel="stylesheet" href="./layout/css/jquery-css/jquery-ui.css">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="./layout/css/bootstrap-css/bootstrap.min.css">

	<!-- Fontawesome icons -->
	<script src="https://kit.fontawesome.com/4088dbf714.js" crossorigin="anonymous"></script>
	<!-- Jquery JS -->
	<script src="./layout/js/jquery-js/jquery-3.5.1.slim.min.js"></script>
	<script src="./layout/js/jquery-js/jquery-3.5.1.min.js"></script>
	<script src="./layout/js/jquery-js/jquery-ui.min.js"></script>
	<!-- Bootstrap JS -->
	<script src="./layout/js/bootstrap-js/popper.min.js"></script>
	<script src="./layout/js/bootstrap-js/bootstrap.min.js"></script>
	<script src="./layout/js/bootstrap-js/bootstrap.bundle.min.js"></script>
	
	<!-- Personal CSS and JS -->
	<link rel="stylesheet" href="./layout/css/style.css">
	<script src="./layout/js/script.js"></script>
</head>

<body>
	<button class='scrollToTopBtn'>
		<i class='fas fa-arrow-up'></i>
	</button>
	<?php
	$contNav->display_view();
	?>
	<main>
		<?php
		echo $modView;
		?>
	</main>
	<?php
	$contFooter->display_view();
	?>
</body>

</html>