<?php
	require __DIR__ . '/../core/init.php';

	$listingSlug = sanitize($_GET['slug']);

	// Prepare the statement -> not querying just yet
	$sql_listings = $conn->prepare('SELECT * FROM listings WHERE listingSlug = ?');

	// Bind the parameters
	$sql_listings->bind_param("s", $listingSlug);

	// Execute query
	$sql_listings->execute();

	// Need get_result() to give the result to
	$sql_listings = $sql_listings->get_result();

	// Exit if we cannot find the post in SQL
	if(!$sql_listings->num_rows > 0) {
		header("Location: /404");
		exit;
	}

	// ------------ IF THERE IS A POST --------------------------

	// Listing Query
	while ($row = $sql_listings->fetch_assoc()) {
		$userID = $row['userID'];
		$textbookID = $row['textbookID'];
		$listingPrice = $row['listingPrice'];
		$listingQuality = $row['listingQuality'];
		$listingDescription = $row['listingDescription'];
		$listingDate = date('jS M Y', strtotime($row['listingDate'])); 

		// Textbooks Query
		$sql_textbooks = $conn->query("SELECT * FROM textbooks LEFT JOIN listings on textbooks.textbookID = listings.textbookID WHERE listings.textbookID = $textbookID");

		while ($row = $sql_textbooks->fetch_assoc()) {
			$textbookISBN = $row['textbookISBN'];
			$textbookTitle = $row['textbookTitle'];
			$textbookYear = $row['textbookYear'];
			$textbookAuthor = $row['textbookAuthor'];
			$textbookEdition = $row['textbookEdition'];
			$textbookPrice = $row['textbookPrice'];
			$textbookURL = $row['textbookURL'];
		}

		// Users Query
		$sql_users = $conn->query("SELECT * FROM users LEFT JOIN listings on users.userID = listings.userID WHERE listings.userID = $userID");
		
		while ($row = $sql_users->fetch_assoc()) {
			$userName = $row['userName'];
			$userPhone = $row['userPhone'];
			$userAgree = $row['userAgree'];
			$userEmail = $row['userEmail'];
			$universityID = $row['universityID'];
		}

		// Universities Query
		$sql_universities = $conn->query("SELECT * FROM universities LEFT JOIN users on universities.universityID = users.universityID WHERE users.universityID = $universityID");
		
		while ($row = $sql_universities->fetch_assoc()) {
			$universityName = $row['universityName'];		
			$universityShortName = $row['universityShortName'];		
			$universityShortCountry = $row['universityShortCountry'];
		}
	}

	// Set Title and Description 
	$title = "Buy $textbookTitle for $$listingPrice";
	$description = "Buy $textbookTitle ($textbookYear) at $universityName for a cheap price of $$listingPrice!";
	$navbar = 'textbooks';


	// Email Preparation
	if (isset($_POST['submit'])) {
		$buyerName = sanitize($_POST['name']);
		$buyerEmail = sanitize($_POST['email']);
		$buyerPhone = sanitize($_POST['phone']);
		$buyerMessage = sanitize($_POST['message']);

		// Recaptcha Check
		require __DIR__ . '/../core/functions/recaptchacheck.php';
		
		// Compose Email
		$to = $userEmail;
		$from = $buyerEmail;
		$subject = "$textbookTitle Textbook for $$listingPrice on unistudymate.com";
		$message = "
		<html>
			<body>
				<h3>Inquiry about the textbook you listed on unistudymate.com</h3>
				<p>Hi $userName, a potential buyer has sent you a message through our secure online form.</p><br/>
				<p><u>Message:</u><br/>" .nl2br($buyerMessage). "</p><br/>
				<p><u>Contact Details:</u><br/>Name: $buyerName <br/>Email: $buyerEmail <br/>Phone: $buyerPhone </p><br/>
				<hr>
				<p>Now it is up to you to organise the meetup! You can simply click the 'Reply' button to email them back and/or phone them.</p><br/>
				<p><b>If you sell this book, remember to log into <a href=\"$configURL\">$configURL</a> and remove your listing so you don't keep getting emails about it. You can also give feedback and donate :)</b></p><br/>
				<p>Remember to tell your friends of $configTitle! <br/><br/><b>Communications Team, </b><br/>contact@unistudymate.com <br/><a href=\"$configURL\">$configURL</a></p>
			</body>
		</html>
		";
		
		require __DIR__ . '/../includes/email.php';
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include '../includes/head.php'; ?>
		<link rel="stylesheet" type="text/css" href="/dist/css/textbooks.css?<?php echo time(); ?>">
        <link rel="stylesheet" type="text/css" href="/dist/css/responsive.css?<?php echo time(); ?>">
	</head>

	<body>
		<!-- Navbar -->
			<?php include '../includes/navbar.php'; ?>

		<!-- Landing -->
			<div class="page-section bg-blue">
				<div class="container landing">
					<?= "<h1 class='landing-heading h-white'>Buy $textbookTitle for $$listingPrice</h1>"; ?>
				</div>
			</div>

		<!-- Textbook Table -->
			<div class="page-section">
				<div class="container">
					<div class='viewtextbook'>
						<?php
							if (!empty($textbookURL)) {
								echo "<img src='$textbookURL' class='search-img' alt='A picture for the book $textbookTitle is available.'>";

							}

							echo "
								<div>
									<table class='table'>
										<tbody>
											<tr><td><strong>Title</strong></td> <td>$textbookTitle</td></tr>
											<tr><td><strong>Year</strong></td> <td>$textbookYear</td></tr>
											<tr><td><strong>Authors</strong></td> <td>$textbookAuthor</td></tr>
											<tr><td><strong>Edition</strong></td> <td>" .ordinal($textbookEdition). "</td></tr>
											<tr><td><strong>Description</strong></td> <td>$listingDescription</td></tr>
											<tr><td><strong>Quality</strong></td> <td>$listingQuality/5</td></tr>
											<tr><td><strong>Current</strong></td> <td>$listingDate</td></tr>
											<tr><td><strong>Price</strong></td> <td><strong>$$listingPrice</strong></td></tr>
											<tr><td><strong>University</strong></td> <td><strong>$universityShortCountry - $universityName</strong></td></tr>
										</tbody>
									</table>
								</div>
							";
						?>
					</div>
                    <?php include '../includes/ads-responsive.php'; ?>
				</div>
			</div>
		
		<!-- Contact Form -->
			<div class="page-section">
				<div class="container">
					<h2 class="h-grey">Contact the Seller:</h2>

					<?php
						if (!empty($userPhone) || $userAgree != 0) {
							echo "<p>$userName has made their phone number available: <a href='tel:$userPhone' class='text-blue'>$userPhone</a>. You can also contact them using the form below.</p>";

						} else {
							echo "<p>$userName has not agreed to make their phone number available. Please email them using the form below.";
						}
					?>
					<form action="" class="details-form" method="POST">
						<div class="form-row">
							<label for="name">Name: <span class="text-red">*</label>
							<input type="text" name="name" required>
						</div>
						<div class="form-row">
							<label for="email">Email: <span class="text-red">*</label>
							<input type="text" name="email" required>
						</div>
						<div class="form-row">
							<label for="phone">Phone: <span class="text-red">*</label>
							<input type="text" name="phone" placeholder="Recommended" required>
						</div>
						<div class="form-row">
							<label for="message">Message: <span class="text-red">*</label>
							<textarea name="message" rows="6" required><?= "Hi $userName, \n \nI would like to purchase this book for $$listingPrice. I am happy to meetup on the campus of $universityName.\n \nThanks" ?></textarea>
						</div>
						<div class="form-row flex justify-content-center">
							<?php include '../includes/recaptcha.php'; ?>
						</div>
						<button name="submit" class="btn btn-dark flex auto">Submit</button>
					</form>
                    <?php include '../includes/ads-responsive.php'; ?>
				</div>
			</div>
		
		<!-- Footer -->
			<?php include '../includes/footer.php'; ?>

	</body>
</html>