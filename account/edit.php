<?php
	require __DIR__ . '/../core/init.php';
	$title = 'Update Listing';
	$description = 'Update your listing, change the textbook description, price and quality!';
	$navbar = 'account';

	if (!isset($_SESSION['userID'])) {
		header('Location: ./login');
		die();
	} else {
		$userID = $_SESSION['userID'];
	}

	//Gather listingID
	$listingID = sanitize($_GET['listingID']);

	if (isset($_POST['update'])) {
		// Gather POST data
		$listingID = sanitize($_POST['listingID']);
		$listingDescription = sanitize($_POST['listingDescription']);
		$listingQuality = sanitize($_POST['listingQuality']);
		$listingPrice = sanitize($_POST['listingPrice']);

		date_default_timezone_set('Australia/Sydney');
		$listingDate = date("Y-m-d H:i:s");

		// Update listing
		$sql_listings = $conn->prepare("UPDATE listings SET listingPrice = ?, listingQuality = ?, listingDescription = ?, listingDate = ? WHERE listingID = ? AND userID = ?");
		$sql_listings->bind_param("ssssss", $listingPrice, $listingQuality, $listingDescription, $listingDate ,$listingID, $userID);
		$sql_listings->execute();

		// header('Location: ./');
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
			<div class="page-section bg-blue">
				<div class="container landing">
					<h1 class="landing-heading h-white">Update Listing</h1>
				</div>
			</div>			

		<!-- Update Listing -->
			<div class="page-section">
				<div class="container">
					<?php
						$sql_listings = $conn->prepare("SELECT * FROM listings WHERE listingID = ? AND userID = ?");
						$sql_listings->bind_param("ss", $listingID, $userID);
						$sql_listings->execute();
						$sql_listings = $sql_listings->get_result();

						if ($row = $sql_listings->fetch_assoc()) {
							$textbookID = $row['textbookID'];
							$listingPrice = $row['listingPrice'];
							$listingQuality = $row['listingQuality'];
							$listingDescription = $row['listingDescription'];

							$sql_textbooks = $conn->query("SELECT * FROM textbooks LEFT JOIN listings ON textbooks.textbookID = listings.textbookID WHERE listings.textbookID = $textbookID");
							
							while ($row = $sql_textbooks->fetch_assoc()){
								$textbookISBN = $row['textbookISBN'];
								$textbookTitle = $row['textbookTitle'];
								$textbookYear = $row['textbookYear'];
								$textbookAuthor = $row['textbookAuthor'];
								$textbookEdition = $row['textbookEdition'];
								$textbookURL = $row['textbookURL'];
							}
							
						} else {
							echo 'Book not found under this user. Please <a href="/account/" class="text-blue">go back</a> Error code x003.';
							echo '</div></div>';
							include '../includes/footer.php';
							die();
						}
						
						echo "
							<div class='flex justify-content-center align-items-center'>
								<img src='$textbookURL' class='search-img' alt='A picture for the book $textbookTitle is available.'>
								<form action='' method='POST'>
									<table class='table'>
										<tbody>
											<tr><td><strong>Title</strong></td> <td>$textbookTitle</td></tr>
											<tr><td><strong>Year</strong></td> <td>$textbookYear</td></tr>
											<tr><td><strong>Authors</strong></td> <td>$textbookAuthor</td></tr>
											<tr><td><strong>Edition</strong></td> <td>" .ordinal($textbookEdition). "</td></tr>
											<tr><td><strong>Description:</strong></td> <td><input type='text' id='listingDescription' name='listingDescription' value='$listingDescription' class='sell-input'></td></tr>
											<tr>
												<td><strong>Quality:</strong></td>
												<td>
													<select name='listingQuality'>
														<option value='1' "; if ($listingQuality == 1) {echo 'selected';} echo ">1 - Tearing Apart</option>
														<option value='2' "; if ($listingQuality == 2) {echo 'selected';} echo ">2 - Poor Quality</option>
														<option value='3' "; if ($listingQuality == 3) {echo 'selected';} echo ">3 - Average</option>
														<option value='4' "; if ($listingQuality == 4) {echo 'selected';} echo ">4 - Good / Contains Highlighting</option>
														<option value='5' "; if ($listingQuality == 5) {echo 'selected';} echo ">5 - Excellent</option>
													</select>
												</td>
											</tr>
											<!-- <tr><td><strong>Subjects/Papers used for:</strong></td> <td><input type='text' id='subjectName' name='subjectName' class='sell-input'></td></tr> -->
											<tr><td><strong>Price ($)<span class='text-red'>*</span></strong></td> <td><input type='number' id='listingPrice' name='listingPrice' value='$listingPrice' class='sell-input'></td></tr>
										</tbody>
									</table>
									<input type='hidden' name='listingID' value='$listingID'>
									<button name='update' class='btn btn-dark flex auto'>Update</button>
								</form>
							</div>
						";
					?>
				</div>
			</div>
			
		<!-- Footer -->
			<?php include '../includes/footer.php'; ?>
	</body>
</html>