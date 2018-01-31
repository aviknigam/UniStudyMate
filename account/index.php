<?php
    require __DIR__ . '/../core/init.php';
    $title = 'Buy and Sell Textbooks';
    $description = 'Buy and sell university textbooks for affordable prices.';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include '../includes/head.php'; ?>
        <link rel="stylesheet" type="text/css" href="/dist/css/textbooks.css?<?php echo time(); ?>">
    </head>

    <body>
        <!-- Navbar -->
            <?php include '../includes/navbar.php'; ?>

        <!-- Landing -->
            <div class="page-section bg-blue">
                <div class="container">