<?php
require __DIR__ . '/../core/init.php';

if (!isset($_SESSION['userID'])) {
    header('Location: ./login');
    die();
} else {
    $userID = $_SESSION['userID'];
}

$listingID = sanitize($_GET['listingID']);

// Date and Time
date_default_timezone_set('Australia/Sydney');
$date = date("Y-m-d H:i:s");

$sql_listings = $conn->prepare("UPDATE listings SET listingDate = '$date' WHERE listingID = ? AND userID = ?");
$sql_listings->bind_param("ss", $listingID, $userID);
$sql_listings->execute();

// Redirect
header('Location: ./');