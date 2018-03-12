<?php
	require __DIR__ . '/core/init.php';
	$title = "Exchange University Textbooks, Notes, Write Subject Reviews and Find Tutors!";
	$description = "Australia's largest university community to buy and sell textbooks, notes and even find tutors for specific university courses!";
	$navbar = 'index';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include 'includes/head.php'; ?>
        <link rel="stylesheet" type="text/css" href="/dist/css/textbooks.css?<?php echo time(); ?>">
        <link rel="stylesheet" type="text/css" href="/dist/css/responsive.css?<?php echo time(); ?>">
	</head>
	
	<body>
		<!-- Navbar -->
			<?php include 'includes/navbar.php'; ?>

		<!-- Landing -->
			<div class="container-fluid page-section text-center bg-blue text-d-white">
				<h1 class="landing-heading h-white">Welcome to <span class="brand">UniStudyMate!</span></h1>
				<p>Exchange textbooks, notes, subject reviews and even find tutors!</p>
				<p>Studying at university made easy!</p>
				<img src="https://cdn190.picsart.com/231198728035212.png?r1024x1024" class="landing-img" alt="Start posting your textbooks for sale today in Australia and New Zealand">
			</div>

		<!-- Create a Free Account -->
			<?php
				if (!isset($_SESSION['userID'])) {
					$sql_users = $conn->query("SELECT * FROM users ORDER BY userID DESC LIMIT 1");

					while ($row = $sql_users->fetch_assoc()) {
						echo "
							<div class='container text-center'>
								<h2 class='h-grey text-center my-4'>$row[userID] members and counting!</h2>
								<a href='/account/register' class='btn btn-primary btn-lg'>Join today!</a>
							</div>
						";
					}
				}
			?>

		<!-- About UniStudyMate -->
			<div class="container-fluid page-section">
				<h2 class="h-grey text-center"><span class="brand">UniStudyMate</span> is free to use and forever will be :)</h2>
			</div>

		<!-- Cards -->
			<div class="container-fluid page-section bg-blue text-d-white d-flex justify-content-around">
				<i class="fas fa-book fa-fw fa-4x"></i>
				<i class="fas fa-comments fa-fw fa-4x"></i>
				<i class="far fa-file-alt fa-fw fa-4x"></i>
				<i class="fas fa-graduation-cap fa-fw fa-4x"></i>
			</div>
		
		<!-- List of Universities -->
			<div class="container page-section">
				<h2 class="h-grey">Choose your University:</h2>
				<p>You can buy and sell textbooks, notes, exchange subject reviews and become a tutor at the following universities!</p>
				<table class="table table-hover">
					<tbody>
						<?php
							$sql_universities = $conn->query("SELECT * FROM universities ORDER BY universityCountry ASC, universityName ASC");

							while ($row = $sql_universities->fetch_assoc()) {
								$universityID = $row['universityID'];
								$universityName = $row['universityName'];
								$universityShortName = $row['universityShortName'];
								$universityShortCountry = $row['universityShortCountry'];

								echo "
									<tr><td><a href='/universities/" .strtolower($universityShortCountry). "/$universityShortName' class='text-blue'>$universityShortCountry - $universityName</td></tr>
								";
							}
						?>
					</tbody>
				</table>
			</div>
		
		<!-- Footer -->
			<?php include 'includes/footer.php'; ?>

	</body>
</html>