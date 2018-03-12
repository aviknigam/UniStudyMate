<?php
	require __DIR__ . '/../core/init.php';
	$title = 'Sell a Textbook';
	$description = 'Sell a textbook for a competitive price!';
	$navbar = 'account';

	if (!isset($_SESSION['userID'])) {
		header('Location: ./login');
		die();
	} else {
		$userID = $_SESSION['userID'];
	}

	if (isset($_POST['sell'])) {
		// Gather POST data
		$listingDescription = sanitize($_POST['listingDescription']);
		$listingQuality = sanitize($_POST['listingQuality']);
		$listingPrice = sanitize($_POST['listingPrice']);
		$textbookISBN = sanitize($_POST['textbookISBN']);

		// Fetch textbookID
		$sql_textbooks = $conn->query("SELECT * FROM textbooks WHERE textbookISBN = $textbookISBN");
		$sql_textbooks= $sql_textbooks->fetch_assoc();

		$textbookID = $sql_textbooks['textbookID'];
		$textbookTitle = clean($sql_textbooks['textbookTitle']);
		$listingType = 'textbook';

		// Date for the slug
		date_default_timezone_set('Australia/Sydney');
		$dateSlug = date("Ymd");
        $listingDate = date("Y-m-d H:i:s");

		// Slug
		$listingSlug = "$textbookTitle-$dateSlug-$userID-$textbookID";

		// Enter into the database
		$sql_listings = $conn->prepare("INSERT INTO listings (userID, textbookID, listingType, listingPrice, listingQuality, listingDescription, listingDate, listingSlug) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		$sql_listings->bind_param("ssssssss", $userID, $textbookID, $listingType, $listingPrice, $listingQuality, $listingDescription, $listingDate, $listingSlug);
		$sql_listings->execute();

		header('Location: ./');
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include '../includes/head.php'; ?>
		<link rel="stylesheet" type="text/css" href="/dist/css/account.css?<?php echo time(); ?>">
        <link rel="stylesheet" type="text/css" href="/dist/css/responsive.css?<?php echo time(); ?>">
	</head>

	<body>
		<!-- Navbar -->
			<?php include '../includes/navbar.php'; ?>

		<!-- Landing -->
			<div class="container-fluid page-section bg-blue text-center">
				<h1 class="landing-heading h-white">Sell a Textbook</h1>
			</div>			

		<!-- Sell Textbook -->
			<div class="container page-section d-flex flex-column align-items-center">
				<form id="textbookSearch">
					<label for="textbookISBN">Enter 13 digit textbook ISBN (no dashes): </label>
					<input type="text" name="textbookISBN" id="textbookISBN" required>
					<button class="btn btn-primary">Search</button>
				</form>
				<div id="loader" class="hide">
					<img src="/dist/img/loader.gif" alt="Loading results...">
				</div>
			</div>
        
        <!-- Search Results -->
            <div class="container page-section">
				<div id="searchResults" class="d-flex flex-column align-items-center">
					<img src="https://isbnsearch.org/images/isbn-location.png" alt="The ISBN is typically found above the barcode">
					<p>Typical location of an ISBN is on the back of the book.</p>
				</div>
			</div>
			
		<!-- Footer -->
			<?php include '../includes/footer.php'; ?>
			<script src="/dist/js/findbook.js"></script>
			
	</body>
</html>