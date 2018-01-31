<?php
	require __DIR__ . '/core/init.php';
	$title = "Exchange Textbooks, Notes and Find Tutors!";
	$description = "Australia's largest university community to buy and sell textbooks, notes and even find tutors for specific university courses!";
	$navbar = 'index';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include 'includes/head.php'; ?>
	</head>
	
	<body>
		<!-- Navbar -->
			<?php include 'includes/navbar.php'; ?>

		<!-- Landing -->
			<div class="page-section bg-blue">
				<div class="container">
					<div class="landing text-d-white">
						<h1 class="landing-heading h-white">Welcome to <span class="brand">UniStudyMate!</span></h1>
						<p>Studying at university made easy! Exchange textbooks, notes, subject reviews and even find tutors!</p>
						<p></p>
					</div>
				</div>
			</div>

		<!-- Scripts -->
			<?php include 'includes/scripts.php'; ?>
			
	</body>
</html>