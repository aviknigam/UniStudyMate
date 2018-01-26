<?php

require __DIR__ . '/core/init.php';
$title = "Uni Study Mate | Exchange Textbooks, Notes and Find Tutors!";
$description = "Australia's largest university community to buy and sell textbooks, notes and even find tutors for specific university courses!";

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		
		<title><?= $title ?></title>
		<link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png?v=kPg5dmjPmB">
		<link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png?v=kPg5dmjPmB">
		<link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png?v=kPg5dmjPmB">
		<link rel="manifest" href="manifest.json?v=kPg5dmjPmB">
		<link rel="mask-icon" href="safari-pinned-tab.svg?v=kPg5dmjPmB" color="#3a004d">
		<link rel="shortcut icon" href="favicon.ico?v=kPg5dmjPmB">
		<meta name="theme-color" content="#ffffff">
		
		<meta name="description" content="<?= $description ?>">
		<meta name="author" content="Uni Study Mate">
		
		<!-- Open Graph -->
		<meta property="og:title" content="<?= $title ?>">
		<meta property="og:type" content="website">
		<meta property="og:url" content="<?= $configurl ?>">
		<meta property="og:image" content="og.jpg">
		<meta property="og:description" content="<?= $description ?>">
		
		<!-- Twitter Summary Card -->
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:title" content="<?= $title ?>" />
		<meta name="twitter:description" content="<?= $description ?>" />
		<meta name="twitter:image" content="android-chrome-256x256.jpg" />

		<script>
  			// (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  			// (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  			// m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  			// })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
			
  			// ga('create', 'UA-98969303-1', 'auto');
  			// ga('send', 'pageview');

		</script>
	</head>
	
	<body>
		<h1>Index Page</h1>
		<a href="/textbooks">textbooks</a>
	</body>
</html>