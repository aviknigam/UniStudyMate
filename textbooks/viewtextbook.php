<?php
require __DIR__ . '/../core/init.php';

$urlSlug = sanitize($_GET['slug']);

// Prepare the statement -> not querying just yet
$stmt = $conn->prepare('SELECT * FROM listings WHERE listingSlug = ?');

// Bind the parameters
$stmt->bind_param("s", $listingSlug);
$listingSlug = $urlSlug;

// Execute query
$stmt->execute();

// Need get_result() to give the result to
$listingResult = $stmt->get_result();

// Exit if we cannot find the post in SQL
if(!$listingResult->num_rows > 0) {
	header("Location: /404");
	exit;
}

// ------------ IF THERE IS A POST --------------------------

// Fetch assoc turns it into a usable array
$listing = $listingResult->fetch_assoc();
$textbookID = $listing['textbookID'];

// LEFT JOIN textbooks
$textbooks_stmt = $conn->query("SELECT * FROM textbooks LEFT JOIN listings on textbooks.textbookID = listings.textbookID WHERE listings.textbookID = $textbookID");
$textbooks = $textbooks_stmt->fetch_assoc();

$title = 'Buy and Sell Textbooks';
$description = 'Buy and sell university textbooks for affordable prices.';
$navbar = 'textbooks';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include '../includes/head.php'; ?>
		<link rel="stylesheet" type="text/css" href="/dist/css/textbooks.css?<?php echo time(); ?>">
	</head>

	<body>
		<!-- Navbar -->
			<?php include '../includes/navbar.php'; ?>

		<!-- Landing -->
			<div class="page-section">
				<div class="container">
					<h1 style="h-grey">Buy <?= $textbooks['textbookTitle']?> for $<?= $listing['listingPrice']; ?></h1>
					<p>ISBN: <?= $textbooks['textbookISBN']?></p>
					<p>Year: <?= $textbooks['textbookYear']?></p>
					<p>Edition: <?= ordinal($textbooks['textbookEdition'])?></p>
					<p>Author: <?= $textbooks['textbookAuthor']?></p>
					<p>Description: <?= $listing['listingDescription']; ?></p>
					<p>Price: $<?= $listing['listingPrice']; ?></p>
					<img src="<?= $textbooks['textbookURL']; ?>" class="" alt="Contact the seller below to buy <?= $textbooks['textbookTitle']?>!">
				</div>
			</div>

		<!-- Footer -->
			<?php include '../includes/footer.php'; ?>

	</body>
</html>