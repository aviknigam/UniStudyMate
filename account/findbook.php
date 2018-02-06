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
if($result->num_rows > 0) {
    $html1 = file_get_contents('https://isbndb.com/book/' . $_GET['url']);
    $html2 = file_get_contents('https://studentvip.com.au/textbooks/' . $_GET['url']);
	
    // Url doesn't allow file_get_contents
    // $html3 = file_get_contents('http://isbnsearch.org/isbn/' . $_GET['url']);
    
    preg_match("'<h1 class=\"textbook-title h3\"><strong>(.*?)</strong>'si", $html2, $textbookTitle);

    if (!preg_match("'<th>Publish Date</th> <td>(.*?)-'si", $html1, $textbookYear)) {
        $textbookYear = '';
    }

    if (!preg_match("'<h5 class=\"textbook-author\">(.*?)</h5>'si", $html2, $textbookAuthor)) {
        // $textbookAuthor = '-';
        echo "no author";
    } else {
        $textbookAuthor = $textbookAuthor[1];
    }
    
    if(!preg_match("'<th>Edition</th> <td>(.*?)</td>'si", $html1, $textbookEdition)) {
        $textbookEdition = '-';
    }

    preg_match("'<object height=\"250px\" width=\"190px\" data=\"(.*?)\" type='si", $html1, $textbookURL);
    
    echo "$textbookTitle[1] <br> $textbookAuthor <br> $textbookYear[1] <br> $textbookEdition[1] <br> <img src=\"$textbookURL[1]\"> <br>";
}





?>

<!-- (.*?)^(\s*.*?\s).*'si" -->