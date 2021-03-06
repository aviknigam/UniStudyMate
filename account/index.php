<?php
	require __DIR__ . '/../core/init.php';
	$title = 'Account';
	$description = 'List your textbooks and notes for sale, add subject reviews and advertise yourself as a University tutor!';
	$navbar = 'account';

	if (!isset($_SESSION['userID'])) {
		header('Location: ./login');
		die();
	} else {
		$userID = $_SESSION['userID'];
	}

	if (isset($_POST['submit'])) {
		$userName = sanitize($_POST['userName']);
		$userEmail = sanitize($_POST['userEmail']);
		$userPhone = sanitize($_POST['userPhone']);
		$universityID = sanitize($_POST['universityID']);
		$userAgree = sanitize($_POST['userAgree']);

		$sql_users = $conn->prepare("UPDATE users SET userName = ?, userEmail = ?, userPhone = ?, universityID = ?, userAgree = ? WHERE userID = $userID");
		$sql_users->bind_param("sssss", $userName, $userEmail, $userPhone, $universityID, $userAgree);
		$sql_users->execute();

		if (!empty($_POST['userPassword'])) {
			$userPassword = sanitize($_POST['userPassword']);
			$encrypted_password = password_hash($userPassword, PASSWORD_DEFAULT);

			$sql_users = $conn->prepare("UPDATE users SET userPassword = ? WHERE userID = $userID");
			$sql_users->bind_param("s", $encrypted_password);
			$sql_users->execute();
		}

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

		<!-- Once logged in -->
			<?php
				$sql_users = $conn->query("SELECT * FROM users WHERE userID = $userID");
				$sql_users = $sql_users->fetch_assoc();

				$userName = $sql_users['userName'];
				$userEmail = $sql_users['userEmail'];
				$userPhone = $sql_users['userPhone'];
				$userAgree = $sql_users['userAgree'];
			?>
		<!-- Landing -->
			<div class="container-fluid page-section bg-blue text-center text-d-white">
				<h1 class="landing-heading h-white">Welcome <?= $userName ?>!</h1>
				<p>Feel free to list your textbooks on sale.</p>
			</div>
		
		<!-- Sell Textbooks -->
			<div class="container page-section d-flex justify-content-center">
				<a href="/account/sell" class="btn btn-primary btn-lg">Sell a Textbook</a>
			</div>

		<!-- Currently on sale -->
				<div class="container">
				
					<?php
						$sql_listings = $conn->query("SELECT * FROM listings WHERE userID = $userID");
						
						if ($sql_listings->num_rows > 0) {
							echo '
								<h2 class="h-grey">You are currently selling:</h2>
								<div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<th class="text-center">Title</th>
												<th class="text-center">Price</th>
												<th class="text-center">Current</th>
												<th class="text-center">Actions</th>
											</tr>
										</thead>
										<tbody>
							';

							while ($row = $sql_listings->fetch_assoc()) {
								$listingID = $row['listingID'];
								$textbookID = $row['textbookID'];
								$listingPrice = $row['listingPrice'];
								$listingSlug = $row['listingSlug'];
								$listingDate = date('jS M Y', strtotime($row['listingDate'])); 

								$sql_textbooks = $conn->query("SELECT * FROM textbooks LEFT JOIN listings ON textbooks.textbookID = listings.textbookID WHERE listings.textbookID = $textbookID");

								while ($row = $sql_textbooks->fetch_assoc()) {
									$textbookTitle = $row['textbookTitle'];	
									$textbookYear = $row['textbookYear'];	

								}

								echo "
										<tr>
											<td class='text-center' style='width: 45%;'><a href='/textbooks/$listingSlug' class='text-blue'>$textbookTitle ($textbookYear)</a></td>
											<td class='text-center' style='width: 10%;'>$$listingPrice</td>
											<td class='text-center' style='width: 15%;'>$listingDate</td>
											<td class='text-center' style='width: 30%;'>
												<a href='renew.php?listingID=$listingID' class='btn btn-success'>Renew</a>
												<a href='edit.php?listingID=$listingID' class='btn btn-primary'>Edit</a>
												<a href='delete.php?listingID=$listingID' class='btn btn-danger' onclick=\"return confirm('Are you sure?');\">Delete</a>
											</td>
										</tr>
								";
							}

							echo '
										</tbody>
									</table>
								</div>
							';

						} else {
							// $sql_listings = $sql_listings->fetch_assoc();
							echo '
								<h2 class="h-grey">You aren\'t currently selling anything!</h2>
							';
						}
					?>
				</div>

		<!-- Account Information -->
			<div class="container page-section">
				<h2 class="h-grey">Contact Details:</h2>
				<p>Update your contact details below</p>
			</div>

			<div class="container">
				<form action="" class="details-form" method="POST">
					<div class="form-group">
						<label for="userName">First Name: <span class="text-red">*</span></label>
						<input type="text" class="form-control" id="userName" name="userName" value="<?= $userName ?>"  required>
					</div>
					<div class="form-group">
						<label for="userEmail">Email: <span class="text-red">*</span></label>
						<input type="email" class="form-control" id="userEmail" name="userEmail" value="<?= $userEmail ?>" required>
					</div>
					<div class="form-group">
						<label for="userPhone">Mobile:</label>
						<input type="tel" class="form-control" id="userPhone" name="userPhone" value="<?= $userPhone ?>">
					</div>
					<label>Allow buyer to see my number:</label><br/>
					<div class="form-check">
						<input type="radio" class="form-check-input" name="userAgree" value="1" <?php if ($userAgree == 1) { echo 'checked';} ?>>
						<label for="userAgree">Yes</label>
					</div>
					<div class="form-check">
						<input type="radio" class="form-check-input" name="userAgree" value="0" <?php if ($userAgree == 0) { echo 'checked';} ?>>
						<label for="userAgree">No</label>
					</div>
					<div class="form-group">
						<label for="universityID">University:</label><br/>
						<select class="form-control" id="universityID" name="universityID">
							<?php
								$currentUniversity = $sql_users['universityID'];
								$sql_universities = $conn->query("SELECT * FROM universities ORDER BY universityCountry ASC, universityName ASC");

								while ($row = $sql_universities->fetch_assoc()) {
									$universityID = $row['universityID'];
									$universityName = $row['universityName'];
									$universityShortCountry = $row['universityShortCountry'];

									echo "
										<option value='$universityID' "; if ($currentUniversity == $universityID) { echo 'selected'; } echo ">$universityShortCountry - $universityName</option>
									";
								}
							?>
						</select>
					</div>

					<div class="form-group">
						<label for="userPassword">Change Password - (optional)</label>
						<input type="password" class="form-control" name="userPassword" placeholder="New Password">
					</div>
					<button name="submit" class="btn btn-primary btn-block mb-5">Update</button>
				</form>
			</div>

		<!-- Footer -->
			<?php include '../includes/footer.php'; ?>
	</body>
</html>