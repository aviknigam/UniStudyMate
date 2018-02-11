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
						<div class="page-section">
							<div class="container">
								<h1 class="h-grey landing-heading">Log in to <span class="brand">UniStudyMate:</span></h1>
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
			?>
		<!-- Landing -->
			<div class="page-section bg-blue">
				<div class="container landing text-d-white">
					<h1 class="h-white landing-heading">Welcome <?= $sql_users['userName'];?>!</h1>
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
								$textbookID = $row['textbookID'];
								$listingPrice = $row['listingPrice'];

								$sql_textbooks = $conn->query("SELECT * FROM textbooks LEFT JOIN listings ON textbooks.textbookID = listings.textbookID WHERE listings.textbookID = $textbookID");

								while ($row = $sql_textbooks->fetch_assoc()) {
									$textbookTitle = $row['textbookTitle'];	

								}

								echo "
									<tr>
										<td>$textbookTitle</td>
										<td>$$listingPrice</td>
									</tr>
								";
							}

							echo '
									</tbody>
								</table>
							';

						} else {
							// $sql_listings = $sql_listings->fetch_assoc();
							echo '
								<h2 class="h-grey">You aren\'t currently selling anything!</h2>
							';
						}

						

					?>
				</div>

		<!-- Footer -->
			<?php include '../includes/footer.php'; ?>
			
	</body>
</html>