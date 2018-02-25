<?php
    require __DIR__ . '/../core/init.php';
    $title = 'Email Sent Successfully';
    $description = 'Email sent successfully to the preferred address.';
    $navbar = 'index';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../includes/head.php'; ?>
        <link rel="stylesheet" type="text/css" href="/dist/css/responsive.css?<?php echo time(); ?>">
    </head>

    <body>
        <!-- Navbar -->
            <?php include '../includes/navbar.php'; ?>

        <!-- Landing -->
            <div class="page-section">
                <div class="container">
                    <h1 class="h-grey">Email sent successfully!</h1>
                    <p>You will be redirected to the <a href="/" class="text-blue">home page</a> in 5 seconds</p>
                </div>
            </div>
        
        <!-- Content -->
            <div class="page-section">
                <div class="container">
                    <?php include 'ads-responsive.php'; ?>
                </div>
            </div>

        <!-- Footer -->
            <script>
                setTimeout(function () {
                    window.location.href= 'https://unistudymate.com';
                },7000);
            </script>
        
    </body>
</html>