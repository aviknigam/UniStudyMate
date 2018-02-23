<?php
require __DIR__ . '/../core/init.php';

$url = sanitize($_GET['url']);

// Prepare the statement -> not querying just yet
$sql = $conn->prepare("SELECT * FROM textbooks WHERE textbookISBN = ?");

// Bind the parameters
$sql->bind_param("s", $textbookISBN);
$textbookISBN = $url;

// Execute query
$sql->execute();

// Need get_result() to give the result to
$result = $sql->get_result();

// If we cannot find the post in the database
if(!$result->num_rows > 0) {
	$html1 = @file_get_contents('https://isbndb.com/book/' . $_GET['url']);
	// URL is for backup
	// $html2 = file_get_contents('https://studentvip.com.au/textbooks/' . $_GET['url']);

	// URL doesn't allow file_get_contents
	// $html3 = file_get_contents('http://isbnsearch.org/isbn/' . $_GET['url']);

	if ($html1 === false AND empty($html1)) {
		echo '<p>Book not found, it will be added manually within 24 hours and you will be notified by email. You could also <a href="/contact" class="text-blue">contact us</a></p>';
	} else {

		if (!preg_match("'<th>Full Title</th> <td>(.*?)</td>'si", $html1, $textbookTitle)) {
			$textbookTitle = '-';
		} else {
			$textbookTitle = $textbookTitle[1];
		}

		if (!preg_match("'<th>Publish Date</th> <td>([0-9]{1,4}).*?</td>'", $html1, $textbookYear)) {
			$textbookYear = '-';
		} else {
			// preg_match("/[0-9]{1,4}/", $textbookYear[1], $textbookYear);
			$textbookYear = $textbookYear[1];
		}

		if (!preg_match_all("'<a href=\"/author/(.*?)\">'si", $html1, $textbookAuthor)) {
			$textbookAuthor = '-';
		} else {
			$textbookAuthor = join("; ", $textbookAuthor[1]);
		}

		if (!preg_match("'<th>Edition</th> <td>(.*?)</td>'si", $html1, $textbookEdition)) {
			$textbookEdition = '-';
		} else {
			$textbookEdition = preg_replace("/[^0-9]/", '', $textbookEdition[1]);
		}

		if (!preg_match("'<object height=\"250px\" width=\"190px\" data=\"(.*?)\"'si", $html1, $textbookURL)) {
			$textbookURL = '-';
		} else {
			$textbookURL = $textbookURL[1];
		}
		
		// Insert into database
		if ($textbookTitle != '-') {
			$sql =	$conn->prepare('INSERT INTO textbooks (`textbookISBN`, `textbookTitle`, `textbookYear`, `textbookAuthor`, `textbookEdition`, `textbookURL`) VALUES (?, ?, ?, ?, ?, ?)');
			$sql->bind_param("ssssss", $textbookISBN, $textbookTitle, $textbookYear, $textbookAuthor, $textbookEdition, $textbookURL);
			$sql->execute();
		}

		echo "
			<div class='flex justify-content-center'>
				<img src='$textbookURL' class='search-img' alt='A picture for $textbookTitle is not available at the moment'>
				<form action='' method='POST'>
					<table class='table'>
						<tbody>
							<tr><td><strong>Title</strong></td> <td>$textbookTitle</td></tr>
							<tr><td><strong>Year</strong></td> <td>$textbookYear</td></tr>
							<tr><td><strong>Authors</td></strong> <td>$textbookAuthor</td></tr>
							<tr><td><strong>Edition</strong></td> <td>" .ordinal($textbookEdition). "</td></tr>
							<tr><td><strong>Description:</strong></td> <td><input type='text' id='listingDescription' name='listingDescription' class='sell-input'></td></tr>
							<tr><td><strong>Quality:</strong></td> <td><select name='listingQuality'><option value='1'>1 - Tearing Apart</option><option value='2'>2 - Poor Quality</option><option value='3'>3 - Average</option><option value='4'>4 - Good / Contains Highlighting</option><option value='5' selected>5 - Excellent</option></select></td></tr>
							<!-- <tr><td><strong>Subjects/Papers used for:</strong></td> <td><input type='text' id='subjectName' name='subjectName' class='sell-input'></td></tr> -->
							<tr><td><strong>Price ($)<span class='text-red'>*</span></strong></td> <td><input type='number' id='listingPrice' name='listingPrice' class='sell-input'></td></tr>
						</tbody>
					</table>
					<input type='hidden' name='textbookISBN' value='$textbookISBN'>
					<button name='sell' class='btn btn-dark flex auto'>Sell</button>
				</form>
			</div>
		";
	}

	// Insert the textbookISBN into `issues` for follow up if it is UNIQUE.
	$sql_issues = $conn->prepare("SELECT textbookISBN FROM issues WHERE textbookISBN = ?");
	$sql_issues->bind_param("s", $textbookISBN);
	$sql_issues->execute();

	if (!($sql_issues->get_result())->num_rows > 0) {
		$userID = $_SESSION['userID'];
		$issueType = 'textbook';

		// Date and Time
		date_default_timezone_set('Australia/Sydney');
		$date = date("Y-m-d H:i:s");

		$sql_issues = $conn->prepare("INSERT INTO `issues` (userID, issueType, textbookISBN, issueDate) VALUES (?, ?, ?, ?)");
		$sql_issues->bind_param("ssss", $userID, $issueType, $textbookISBN, $date);
		$sql_issues->execute();
	}

} else {
	// If we found a result from the database
	$row = $result->fetch_assoc();

	echo "
		<div class='flex justify-content-center align-items-center'>
			<img src='$row[textbookURL]' class='search-img' alt='A picture for the book $row[textbookTitle] is available.'>
			<form action='' method='POST'>
				<table class='table'>
					<tbody>
						<tr><td><strong>Title</strong></td> <td>$row[textbookTitle]</td></tr>
						<tr><td><strong>Year</strong></td> <td>$row[textbookYear]</td></tr>
						<tr><td><strong>Authors</td></strong> <td>$row[textbookAuthor]</td></tr>
						<tr><td><strong>Edition</strong></td> <td>" .ordinal($row['textbookEdition']). "</td></tr>
						<tr><td><strong>Description:</strong></td> <td><input type='text' id='listingDescription' name='listingDescription' class='sell-input'></td></tr>
						<tr><td><strong>Quality:</strong></td> <td><select name='listingQuality'><option value='1'>1 - Tearing Apart</option><option value='2'>2 - Poor Quality</option><option value='3'>3 - Average</option><option value='4'>4 - Good / Contains Highlighting</option><option value='5' selected>5 - Excellent</option></select></td></tr>
						<!-- <tr><td><strong>Subjects/Papers used for:</strong></td> <td><input type='text' id='subjectName' name='subjectName' class='sell-input'></td></tr> -->
						<tr><td><strong>Price ($)<span class='text-red'>*</span></strong></td> <td><input type='number' id='listingPrice' name='listingPrice' class='sell-input'></td></tr>
					</tbody>
				</table>
				<input type='hidden' name='textbookISBN' value='$textbookISBN'>
				<button name='sell' class='btn btn-dark flex auto'>Sell</button>
			</form>
		</div>
	";
}
?>