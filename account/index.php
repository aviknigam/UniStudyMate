<?php
	require __DIR__ . '/../core/init.php';
	$title = 'Account';
	$description = 'Account';
	$navbar = 'account';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include '../includes/head.php'; ?>
		<link rel="stylesheet" type="text/css" href="/dist/css/account.css?<?php echo time(); ?>">
	</head>

	<body>
		<!-- Navbar -->
			<?php include '../includes/navbar.php'; ?>

		<!-- Landing -->
			<div class="page-section">
				<div class="container">
				
				</div>
			</div>

		<!-- Footer -->
			<?php include '../includes/footer.php'; ?>
			
		<!-- Scripts -->
			<?php include '../includes/scripts.php'; ?>
			
	</body>
</html>