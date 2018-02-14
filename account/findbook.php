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
	
	if (!file_exists('https://isbndb.com/book/' . $_GET['url'])) {
		echo '<p>Book not found, please <a href="/contact" class="text-blue">contact us</a> to have it added manually.</p>';
	} else {
		$html1 = file_get_contents('https://isbndb.com/book/' . $_GET['url']);
		
		// URL is for backup
		// $html2 = file_get_contents('https://studentvip.com.au/textbooks/' . $_GET['url']);

		// URL doesn't allow file_get_contents
		// $html3 = file_get_contents('http://isbnsearch.org/isbn/' . $_GET['url']);

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
				<table class='table'>
					<tbody>
						<tr><td><strong>Title</strong></td> <td>$textbookTitle</td>
						<tr><td><strong>Year</strong></td> <td>$textbookYear</td>
						<tr><td><strong>Authors</td></strong> <td>$textbookAuthor</td>
						<tr><td><strong>Edition</strong></td> <td>" .ordinal($textbookEdition). "</td>
					</tbody>
				</table>
			</div>";
	}

} else {
	// If we found a result from the database
	$row = $result->fetch_assoc();

	echo "
		<div class='flex justify-content-center'>
			<img src='$row[textbookURL]' class='search-img' alt='A picture for $row[textbookTitle] is not available at the moment'>
			<table class='table'>
				<tbody>
					<tr><td><strong>Title</strong></td> <td>$row[textbookTitle]</td>
					<tr><td><strong>Year</strong></td> <td>$row[textbookYear]</td>
					<tr><td><strong>Authors</td></strong> <td>$row[textbookAuthor]</td>
					<tr><td><strong>Edition</strong></td> <td>" .ordinal($row['textbookEdition']). "</td>
				</tbody>
			</table>
		</div>
	";
}


?>