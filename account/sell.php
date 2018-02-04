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
		<!-- <link rel="stylesheet" type="text/css" href="/dist/css/account.css?<?php echo time(); ?>"> -->
	</head>

	<body>
		<!-- Navbar -->
			<?php include '../includes/navbar.php'; ?>

		<!-- Landing -->
			<div class="page-section">
				<div class="container">
                    <form id="textbookSearch">
                        <label for="textbookISBN">Textbook ISBN (no dashes): </label>
                        <input type="text" name="textbookISBN" id="textbookISBN">
                        <button id="search">Search</button>
                    </form>
				</div>
			</div>
        
        <!-- Search Results -->
            <div class="page-section">
                <div class="container">
                    <div id="searchResults">

                    </div>
                </div>
            </div>
		<!-- Footer -->
			<?php include '../includes/footer.php'; ?>
			<script src="/dist/js/findbook.js"></script>
			
	</body>
</html>