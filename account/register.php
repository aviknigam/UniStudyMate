<?php
	require __DIR__ . '/../core/init.php';
	$title = 'Create Account';
	$description = 'Create an Account for FREE to post your textbooks, notes, write subject reviews and make yourself available as a tutor!';
	$navbar = 'account';

    if (isset($_SESSION['userID'])) {
        header('Location: ./');
    }
    
    if(isset($_POST['submit'])) {

        // Gather user input
        $userName = sanitize($_POST['userName']);
        $userEmail = sanitize($_POST['email']);
        $userPhone = sanitize($_POST['phone']);
        $userPassword = sanitize($_POST['password']);
        $universityID = sanitize($_POST['universityID']);

        // Check empty fields
        if (empty($userName) || empty($userEmail) || empty($userPassword)) {
            echo 'Please fill in all the fields with valid information. Error code x002.';
            die();
        }

        require __DIR__ . '/../core/functions/recaptchacheck.php';

        // Check Email
        $sql_users = $conn->prepare("SELECT userEmail FROM users WHERE userEmail = ?");
        $sql_users->bind_param("s", $userEmail);
        $sql_users->execute();
        $check_email = ($sql_users->get_result())->num_rows;

        // Check Phone
        $sql_users = $conn->prepare("SELECT userPhone FROM users WHERE userPhone = ?");
        $sql_users->bind_param("s", $userPhone);
        $sql_users->execute();
        $check_phone = ($sql_users->get_result())->num_rows;

        // Run Check IF Statements
        if ($check_email > 0) {
            echo 'Email is already taken. If you already have an account then please <a href="/account/login">login</a> or recover your account. Error code x003.';
            die();
        } 

        if (!empty($phone)) {
            if ($check_phone > 0) {
                echo 'The phone number is already taken. If you already have an account then please <a href="/account/login">login</a>. Error code x004. <p>If you believe that another user has taken your phone number, please <a href="contact.php">contact us</p></a>';
                die();
            }
        } 

        // Date and Time
        date_default_timezone_set('Australia/Sydney');
        $date = date("Y-m-d H:i:s");

        $encrypted_password = password_hash($userPassword, PASSWORD_DEFAULT);
        
        $sql_users = $conn->prepare("INSERT INTO `users` (userName, userEmail, userPhone, userPassword, universityID, userDate) VALUES (?, ?, ?, ?, ?, ?)");
        $sql_users->bind_param("ssssss", $userName, $userEmail, $userPhone, $encrypted_password, $universityID, $date);
        $sql_users->execute();

        // Check row association 
        $sql_users = $conn->prepare("SELECT * FROM users WHERE userEmail = ?");
        $sql_users->bind_param("s", $userEmail);
        $sql_users->execute();
        $row_finish_user = ($sql_users->get_result())->fetch_assoc();

        $_SESSION['userID'] = $row_finish_user['userID'];
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
			<div class="page-section bg-blue">
				<div class="container landing text-d-white">
                    <h1 class="landing-heading h-white">Create an Account</h1>
                    <p><span class="brand">UniStudyMate</span> is absolutely free and easy to use!</p>
					<p>Simply sign up, advertise your books and notes and let the buyer contact you!</p>
				</div>
			</div>

		<!-- Create an Account Form -->
			<div class="page-section">
				<div class="container">
                    <form action="" method="POST" class="page-section details-form">
                        <div class="form-row">
                            <label for="userName">First Name: <span class="text-red">*</span></label>
                            <input type="text" id="userName" name="userName" >
                        </div>
                        <div class="form-row">
                            <label for="userEmail">Email: <span class="text-red">*</span></label>
                            <input type="email" id="userEmail" name="email" required>
                        </div>
                        <div class="form-row">
                            <label for="userPhone">Mobile: (Recommended)</label>
                            <input type="tel" id="userPhone" name="phone">
                        </div>
                        <div class="form-row">
                            <label for="universityID">University: <span class="text-red">*</span></label><br/>
                            <select id="universityID" name="universityID">
                                <option>-- Select a University --</option>
                                <?php
                                    $sql_universities = $conn->query("SELECT * FROM universities ORDER BY universityCountry ASC, universityName ASC");

                                    while ($row = $sql_universities->fetch_assoc()) {
                                        $universityID = $row['universityID'];
                                        $universityName = $row['universityName'];
                                        $universityShortCountry = $row['universityShortCountry'];

                                        echo "
                                            <option value='$universityID'>$universityShortCountry - $universityName</option>
                                        ";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-row">
                            <label for="userPassword">Password <span class="text-red">*</span></label>
                            <input type="password" name="password">
                        </div>
                        <div class="form-row flex justify-content-center">
                            <?php require __DIR__ . '/../includes/recaptcha.php'; ?>
                        </div>
                        <button name="submit" class="btn btn-dark btn-block" style="margin-bottom: 50px;">Sign Up</button>
                    </form>
                </div>
            </div>
        
        <!-- Footer -->
            <?php include '../includes/footer.php' ?>