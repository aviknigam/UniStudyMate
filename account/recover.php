<?php
	require __DIR__ . '/../core/init.php';
	$title = "Recover your Account";
	$description = "Recover your password so you can quickly get back to selling university resources at campuses across Australia and New Zealand.";
	$navbar = 'account';
	
	if (isset($_SESSION['userID'])) {
		header('Location: ./');
	}

	if (isset($_POST['sendEmail'])) {
		$userEmail = sanitize($_POST['email']);
		
		// reCaptcha Checks
		require __DIR__ . '/../core/functions/recaptchacheck.php';
		
		$sql_users = $conn->prepare("SELECT * FROM users WHERE userEmail = ?");
		$sql_users->bind_param("s", $userEmail);
		$sql_users->execute();
		
		while ($row = ($sql_users->get_result())->fetch_assoc()) {
			$userID = $row['userID'];
			$userName = $row['userName'];
			$token = bin2hex(random_bytes(16));

			$sql_recover = $conn->prepare("INSERT INTO recover (userID, userToken) VALUES (?, ?)");
			$sql_recover->bind_param("ss", $userID, $token);
			$sql_recover->execute();

			// Compose Email
			$to = $userEmail;
			$from = 'contact@unistudymate.com';
			$subject = "Reset your Password on $configTitle";
			$message = "
			<html>
				<body>
					<h3>If you have forgotten your password, use the link below to reset it</h3>
					<p>Hi $userName, it seems like you have requested an email to reset your password through our secure online form.</p><br/>
					<hr>
					<br/>
					<p><b><a href='https://unistudymate.com/account/recover?userID=$userID&token=$token'>https://unistudymate.com/account/recover?userID=$userID&token=$token</a></b></p><br/>
					<p>Please use the link above to reset your password. If you cannot click on it, please cut and paste it into your browser address bar.</p><br/>
					<p><b>If you did not request a password change, simply ignore this email. Your account is protected at all times.</b></p><br/>
					<p>Remember to tell your friends of $configTitle! <br/><br/><b>Communications Team, </b><br/>contact@unistudymate.com <br/><a href=\"$configURL\">$configURL</a></p>
				</body>
			</html>
			";
			
			require __DIR__ . '/../includes/email.php';
		}
	}

	if (isset($_POST['resetPassword'])) {
		$userID = sanitize($_POST['userID']);
		$userToken = sanitize($_POST['token']);
		$userPassword = sanitize($_POST['userPassword']);

		// reCaptcha Checks
		require __DIR__ . '/../core/functions/recaptchacheck.php';

		$sql_recover = $conn->prepare("SELECT * FROM recover WHERE userID = ? AND userToken = ?");
		$sql_recover->bind_param("ss", $userID, $userToken);
		$sql_recover->execute();

		if ($row = ($sql_recover->get_result())->fetch_assoc()) {
			$encrypted_password = password_hash($userPassword, PASSWORD_DEFAULT);

			$sql_users = $conn->prepare("UPDATE users SET userPassword = ? WHERE userID = $userID");
			$sql_users->bind_param("s", $encrypted_password);
			$sql_users->execute();

			$sql_recover = $conn->prepare("UPDATE recover SET userToken = NULL WHERE userID = $userID AND userToken = ?");
			$sql_recover->bind_param("s", $userToken);
			$sql_recover->execute();

			header('Location: ./');
		} else {
			echo "<h2>User not found</h2> <p>Please fill in the Request Password Reset form again. Thanks</p>";
		}
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
			<div class="container-fluid page-section text-center bg-blue text-d-white">
				<h1 class="landing-heading h-white">Forgot your Password?</h1>
			</div>
		
		<!-- Request Form -->
			<?php
				if (!isset($_GET['userID'], $_GET['token'])) {
					echo '
						<div class="container page-section">
							<form action="" method="POST" class="details-form">
								<div class="form-group">
									<label>Enter the email used to register to <span class="brand">UniStudyMate</span> below: <span class="text-red">*</span></label>
									<input type="text" class="form-control" name="email">
								</div>
								<div class="form-group d-flex justify-content-center">';
									require __DIR__ . '/../includes/recaptcha.php';
								echo '</div>
								<button type="submit" name="sendEmail" class="btn btn-primary d-flex auto">Send Reset Password Email</button>
							</form>
						</div>
					';
				}
			?>

		<!-- Reset Password Form -->
			<?php
				if (isset($_GET['userID'], $_GET['token'])) {
					$userID = sanitize($_GET['userID']);
					$token = sanitize($_GET['token']);
					
					echo '
						<div class="container page-section">
							<form action="" method="POST" class="details-form">
								<div class="form-group">
									<label>New Password: <span class="text-red">*</span></label>
									<input type="password" class="form-control" name="userPassword">
								</div>
								<input type="hidden" name="userID" value="' .$userID. '">
								<input type="hidden" name="token" value="' .$token. '">
								<div class="form-group d-flex justify-content-center">';
									require __DIR__ . '/../includes/recaptcha.php';
								echo '</div>
								<button type="submit" name="resetPassword" class="btn btn-primary d-flex auto">Reset Password</button>
							</form>
						</div>
					';
				}
			?>

		<!-- Footer -->
			<?php include '../includes/footer.php'; ?>
	</body>
</html>