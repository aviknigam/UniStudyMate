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

// Exit if we cannot find the post in SQL
if(!$result->num_rows > 0) {
	header("Location: /404");
    exit;
}

// $html = file_get_contents('https://isbndb.com/book/' . $_GET['url']);

// Url doesn't allow file_get_contents
// $html = file_get_contents('http://isbnsearch.org/isbn/' . $_GET['url']);

preg_match("'<h1>(.*?)</h1>'si", $html, $textbookTitle);
preg_match("'<p><strong>Published:</strong>^(\s*.*?\s).*'si", $html, $textbookYear);
preg_match("'<p><strong>Authors:</strong> (.*?)</p>'si", $html, $textbookAuthor);
preg_match("'<p><strong>Edition:</strong> (.*?)</p>'si", $html, $textbookEdition);
preg_match("'<img src=\"(.*?)\" alt'si", $html, $textbookURL);

echo "$textbookTitle[1] <br> $textbookAuthor[1] <br> $textbookYear[1] <br> $textbookEdition[1] <br> $textbookURL[1] <br>";



?>