<?php
	require __DIR__ . '/../core/init.php';
	$title = 'Log In';
	$description = 'Log in to post your textbooks, notes, write subject reviews and make yourself available as a tutor ';
	$navbar = 'account';

    if (isset($_SESSION['userID'])) {
        header('Location: ./');
    }
    
    if(isset($_POST['submit'])) {
		$userEmail = sanitize($_POST['email']);
		$userPassword = sanitize($_POST['password']);
		
        require __DIR__ . '/../core/functions/recaptchacheck.php';

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
        <link rel="stylesheet" type="text/css" href="/dist/css/responsive.css?<?php echo time(); ?>">
	</head>

	<body>
		<!-- Navbar -->
			<?php include '../includes/navbar.php'; ?>

		<!-- Landing -->
			<div class="container-fluid page-section bg-blue text-center text-d-white">
				<h1 class="landing-heading h-white">Log in to <span class="brand">UniStudyMate:</span></h1>
				<p>Due to the recent merge from utstextbooks.com, your same login will work!</p>
			</div>

		<!-- Login Form -->
			<div class="container page-section">
				<form action="" method="POST" class="page-section login-form">
					<div class="form-group">
						<label for="email">Email: <span class="text-red">*</span></label>
						<input type="email" class="form-control" name="email" required autofocus>
					</div>
					<div class="form-group">
						<label for="password">Password: <span class="text-red">*</span></label>
						<input type="password" class="form-control" name="password" required>
					</div>
					<div class="form-group d-flex justify-content-center">
						<?php require __DIR__ . '/../includes/recaptcha.php'; ?>
					</div>
					<button class="btn btn-success btn-block" name="submit">Login</button>
				</form>
				<div class="d-flex flex-column align-items-center text-grey">
					<p><a href="/account/recover" class="text-blue">Forgot password?</a></p>
					<a href="/account/register" class="btn btn-primary">Sign up for free</a>
				</div>
			</div>

		<!-- Footer -->
			<?php include '../includes/footer.php'; ?>

	</body>
</html>