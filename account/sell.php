<?php
	require __DIR__ . '/../core/init.php';
	$title = 'Sell a Textbook';
	$description = 'Sell a textbook for a competitive price!';
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
			<div class="page-section bg-blue">
				<div class="container landing">
					<h1 class="h-white landing-heading">Sell a Textbook</h1>
				</div>
			</div>			

		<!-- Sell Textbook -->
			<div class="page-section">
				<div class="container flex ffcolwrap align-items-center">
                    <form id="textbookSearch">
                        <label for="textbookISBN">Enter 13 digit textbook ISBN (no dashes): </label>
                        <input type="text" name="textbookISBN" id="textbookISBN">
                        <button class="btn btn-dark">Search</button>
					</form>
					<div id="loader" class="hide">
						<img src="/dist/img/loader.gif" alt="Loading results...">
					</div>
				</div>
			</div>
        
        <!-- Search Results -->
            <div class="page-section">
                <div class="container">
                    <div id="searchResults" class="flex ffcolwrap align-items-center">
						<img src="https://isbnsearch.org/images/isbn-location.png" alt="The ISBN is typically found above the barcode">
						<p>Typical location of an ISBN is on the back of the book.</p>
                    </div>
                </div>
            </div>
		<!-- Footer -->
			<?php include '../includes/footer.php'; ?>
			<script src="/dist/js/findbook.js"></script>
			
	</body>
</html>