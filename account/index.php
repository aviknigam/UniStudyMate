<?php
	require __DIR__ . '/../core/init.php';
	$title = 'View Account';
	$description = 'List your textbooks and notes for sale, add subject reviews and advertise yourself as a University tutor!';
	$navbar = 'account';

	if(isset($_POST['submit'])) {
		$userEmail = sanitize($_POST['email']);
		$userPassword = sanitize($_POST['password']);
		
		$sql = $conn->prepare("SELECT * FROM users WHERE userEmail = ?");
		$sql->bind_param("s", $userEmail);
		$sql->execute();

		$row_find_hash = ($sql->get_result())->fetch_assoc();
		$hash_password = $row_find_hash['userPassword'];
		$hash = password_verify($userPassword, $hash_password);

		// Check row association 
		$stmt_sql_login_user = $conn->prepare("SELECT * FROM users WHERE userEmail = ? AND userPassword = ?");
		$stmt_sql_login_user->bind_param("ss", $userEmail, $hash_password);
		$stmt_sql_login_user->execute();


		// Run Check IF statement
		if ($hash == 0) {
			echo 'Incorrect email or password, please <a href="/account/" style="color: blue;">try logging in again.</a> If you are not a member yet then you can <a href="/account/register" style="color: blue;">Sign up for free!</a>';

		} elseif (!$row_login_user = ($stmt_sql_login_user->get_result())->fetch_assoc()) {
			echo 'Incorrect email or password, please <a href="/account/" style="color: blue;">try logging in again.</a> If you are not a member yet then you can <a href="/account/register" style="color: blue;">Sign up for free!</a>';

		} else { 
			$_SESSION['userID'] = $row_login_user['userID'];
			header("Location: ./");
		}
	}
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

		<!-- If not logged in, display login form -->
			<?php
				if(!isset($_SESSION['userID'])) {
					echo '
						<div class="page-section bg-blue">
							<div class="container landing text-d-white">
								<h1 class="landing-heading h-white">Log in to <span class="brand">UniStudyMate:</span></h1>
								<p>Due to the recent merge from utstextbooks.com, your same login will work!</p>
							</div>
						</div>

						<div class="page-section">
							<div class="container">
								<form action="" method="POST" class="page-section login-form">
									<input type="email" name="email" placeholder="Email address" required autofocus>
									<input type="password" name="password" placeholder="Password" required>
									<button class="btn btn-dark btn-block" name="submit">Login</button>
								</form>
								<div class="flex ffcolwrap align-items-center text-grey">
									<p><a href="/account/recover">Forgot password?</a></p>
									<a href="/account/register"><button class="btn btn-light">Sign up for free</button></a>
								</div>
							</div>
						</div>
					';
					include '../includes/footer.php';
					die();
				}
			?>
		<!-- Once logged in -->
			<?php
				$userID = $_SESSION['userID'];
				$sql_users = $conn->query("SELECT * FROM users WHERE userID = $userID");
				$sql_users = $sql_users->fetch_assoc();

				$userName = $sql_users['userName'];
				$userEmail = $sql_users['userEmail'];
				$userPhone = $sql_users['userPhone'];
				$userAgree = $sql_users['userAgree'];
			?>
		<!-- Landing -->
			<div class="page-section bg-blue">
				<div class="container landing text-d-white">
					<h1 class="h-white landing-heading">Welcome <?= $userName ?>!</h1>
					<p>Feel free to list your textbooks on sale.</p>
				</div>
			</div>
		
		<!-- Sell Textbooks -->
			<div class="page-section">
				<div class="container flex justify-content-center">
					<a href="/account/sell" class="btn btn-dark">Sell a Textbook</a>
				</div>
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
												<th class="center">Title</th>
												<th class="center">Price</th>
												<th class="center">Current</th>
												<th class="center">Actions</th>
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
										<td style='width: 45%;'><a href='/textbooks/$listingSlug' class='text-blue'>$textbookTitle ($textbookYear)</a></td>
										<td style='width: 10%;'>$$listingPrice</td>
										<td style='width: 15%;'>$listingDate</td>
										<td style='width: 30%;' class='flex'>
											<a href='renew.php?listingID=$listingID' class='btn btn-dark'>Renew</a>
											<a href='edit.php?listingID=$listingID' class='btn btn-dark'>Edit</a>
											<a href='delete.php?listingID=$listingID' class='btn btn-dark' onclick=\"return confirm('Are you sure?');\">Delete</a>
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
			<div class="page-section">
				<div class="container">
					<h2 class="h-grey">Contact Details:</h2>
					<p>Update your contact details below</p>
				</div>
			</div>

			<div class="container">
				<form action="edit-account" class="login-form" method="POST">
					<div class="form-row">
						<label for="userName">First Name:</label>
						<input type="text" id="userName" name="userName" value="<?= $userName ?>"  required>
					</div>
					<div class="form-row">
						<label for="userEmail">Email:</label>
						<input type="email" id="userEmail" name="userEmail" value="<?= $userEmail ?>" required>
					</div>
					<div class="form-row">
						<label for="userPhone">Mobile:</label>
						<input type="tel" id="userPhone" name="userPhone" value="<?= $userPhone ?>" required>
					</div>
					<div class="form-row">
						<label for="universityID">University:</label>
						<select id="universityID" name="universityID">
							<?php
								$sql_universities = $conn->prepare("SELECT * FROM universities")
							?>
						</select>
					</div>
					<div class="form-row">
						<label for=""></label>
						<input type="number" id="" name="" value="<?= $sql_users['userAgree']; ?>">
					</div>
					<input type="password" placeholder="New Password">
					<input type="password" placeholder="Retype New Password">
				</form>
			</div>

		<!-- Footer -->
			<?php include '../includes/footer.php'; ?>
			
	</body>
</html>