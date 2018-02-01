<?php
require __DIR__ . '/../core/init.php';

$urlSlug = sanitize($_GET['slug']);

// Prepare the statement -> not querying just yet
$stmt = $conn->prepare('SELECT * FROM listings WHERE listingSlug = ?');

// Bind the parameters
$stmt->bind_param("s", $listingSlug);
$listingSlug = $urlSlug;

// Execute query
$stmt->execute();

// Need get_result() to give the result to
$result = $stmt->get_result();

// Exit if we cannot find the post in SQL
if(!$result->num_rows > 0) {
	header("Location: /404");
    exit;
}

// ------------ IF THERE IS A POST --------------------------

// Fetch assoc turns it into a usable array
$row = $result->fetch_assoc();

$title = 'Buy and Sell Textbooks';
$description = 'Buy and sell university textbooks for affordable prices.';
$navbar = 'textbooks';
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
                <div class="container landing text-d-white">
                    <h1>$ <?= $row['listingPrice']; ?></h1>
                </div>
            </div>
          
    </body>
</html>