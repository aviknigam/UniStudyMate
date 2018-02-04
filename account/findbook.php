<?php
require __DIR__ . '/../core/init.php';

// Use StudentVIP and delete this line

// $html = file_get_contents('https://isbndb.com/book/' . $_GET['url']);
$html = file_get_contents('http://isbnsearch.org/isbn/' . $_GET['url']);

preg_match("'<h1>(.*?)</h1>'si", $html, $textbookTitle);
preg_match("'<p><strong>Published:</strong>^(\s*.*?\s).*'si", $html, $textbookYear);
preg_match("'<p><strong>Authors:</strong> (.*?)</p>'si", $html, $textbookAuthor);
preg_match("'<p><strong>Edition:</strong> (.*?)</p>'si", $html, $textbookEdition);
preg_match("'<img src=\"(.*?)\" alt'si", $html, $textbookURL);

echo "$textbookTitle[1] <br> $textbookAuthor[1] <br> $textbookYear[1] <br> $textbookEdition[1] <br> $textbookURL[1] <br>";



?>