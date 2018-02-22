<?php
require __DIR__ . '/../core/init.php';

if (!isset($_SESSION['userID'])) {
    header('Location: ./login');
    die();
} else {
    $userID = $_SESSION['userID'];
}

//Gather listingID
$listingID = sanitize($_GET['listingID']);

//Check is listing exists
$sql_listings = $conn->prepare("DELETE FROM listings WHERE listingID = ? AND userID = ?");
$sql_listings->bind_param("ss", $listingID, $userID);
$sql_listings->execute();

header('Location: ./');