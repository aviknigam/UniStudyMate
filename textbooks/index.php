<?php
    require __DIR__ . '/../core/init.php';
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
                <div class="container">
                    <div class="landing text-d-white">
                        <h1 class="landing-heading h-white">Textbook Marketplace</h1>
                        <p>Buy and sell university textbooks for no extra commission!</p>
                        <p>Simply <a href="/account/register" class="btn btn-dark btn-transparent btn-block">create an account</a> and advertise your books for free!</p>
                        <img src="/dist/img/bird-happy.png" alt="Earn a few extra bucks which are just lying around">
                    </div>    
                </div>
            </div>

        <!-- Browse Textbooks -->
            <div class="page-section">
                <div class="container flex ffcolwrap align-items-center text-align-center">
                    <?php
                        if (!isset($_SESSION['userID'])) {
                            echo '<a href="/account/register" class="btn btn-dark btn-block">Create an Account</a>';
                        } else {
                            echo '<a href="/account/sell" class="btn btn-dark btn-block">Sell a Textbook</a>';
                        }
                    ?>
                    <h2 class="h-grey">Browse the Latest Textbooks</h2>
                    <input type="text" id="search" placeholder="Type to filter !">
                </div>
            </div>

        <!-- List of Textbooks -->
            <div class="page-section">
                <div class="container">
                    <div class="table-responsive">
                        <table id="table" class="table table-hover">
                            <tbody>
                                <?php
                                    // Listings Query
                                    $listings_query = "SELECT * FROM listings WHERE listingType = 'textbook' ORDER BY listingID DESC";
                                    $listings_query = $conn->query($listings_query);

                                    while ($row = $listings_query->fetch_assoc()) {
                                        $textbookID = $row['textbookID'];
                                        $universityID = $row['universityID'];
                                        $listingPrice = $row['listingPrice'];
                                        $listingQuality = $row['listingQuality'];
                                        $listingDescription = $row['listingDescription'];
                                        $listingSlug = $row['listingSlug'];
                                        
                                        // Textbooks Query
                                        $textbooks_query = "SELECT * FROM textbooks LEFT JOIN listings ON textbooks.textbookID = listings.textbookID WHERE listings.textbookID = $textbookID";
                                        $textbooks_query = $conn->query($textbooks_query);

                                        while ($row = $textbooks_query->fetch_assoc()) {
                                            $textbookISBN = $row['textbookISBN'];
                                            $textbookTitle = $row['textbookTitle'];
                                            $textbookYear = $row['textbookYear'];
                                            $textbookAuthor = $row['textbookAuthor'];
                                            $textbookEdition = $row['textbookEdition'];
                                            $textbookPrice = $row['textbookPrice'];
                                            $textbookURK = $row['textbookURL'];
                                        }

                                        // University Query
                                        $universities_query = "SELECT * FROM universities LEFT JOIN listings ON universities.universityID = listings.universityID WHERE listings.universityID = $universityID";
                                        $universities_query = $conn->query($universities_query);
                                        
                                        while ($row = $universities_query->fetch_assoc()) {
                                            $universityName = $row['universityName'];
                                            $universityShortName = $row['universityShortName'];
                                        }
                                        
                                        // Each Table Row
                                        echo "
                                            <tr>
                                                <td style='width: 40%;'><a href='/textbooks/$listingSlug' class='textbook-link'>$textbookTitle ($textbookYear)</a><br /><br /><span>$textbookAuthor</span><br /><span>Edition: " .ordinal($textbookEdition). "</span><br /><span><strong>Quality: $listingQuality/5</strong></span></td>
                                                <td style='width: 40%;'><span><strong>$universityName</strong></span><br /><br /><span>$listingDescription</span></td>
                                                <td width='10%'><span>ISBN: <br /><br />$textbookISBN</span></td>
                                                <td width='10%' style='vertical-align: middle'><a href='/textbooks/$listingSlug' class='btn btn-dark btn-block'>Buy for &dollar;$listingPrice</a></td>
                                            </tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

		<!-- Footer -->
			<?php include '../includes/footer.php'; ?>
            <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
            <script src="/dist/js/filter.js"></script>
    </body>
</html>