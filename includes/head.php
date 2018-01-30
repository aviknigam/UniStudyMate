<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title><?= 'UniStudyMate | ' .$title ?></title>
        <meta name="description" content="<?= $description ?>">
        <meta name="author" content="UniStudyMate">

        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="/dist/img/favicon/apple-touch-icon.png?v=kPg5dmjPmB">
        <link rel="icon" type="image/png" sizes="32x32" href="/dist/img/favicon/favicon-32x32.png?v=kPg5dmjPmB">
        <link rel="icon" type="image/png" sizes="16x16" href="/dist/img/favicon/favicon-16x16.png?v=kPg5dmjPmB">
        <link rel="manifest" href="/dist/img/favicon/manifest.json?v=kPg5dmjPmB">
        <link rel="mask-icon" href="/dist/img/favicon/safari-pinned-tab.svg?v=kPg5dmjPmB" color="#3a004d">
        <link rel="shortcut icon" href="/dist/img/favicon/favicon.ico?v=kPg5dmjPmB">
        <meta name="theme-color" content="#ffffff">

        <!-- Google Analytics -->
        <?php
            if ($googleAnalytics == 1) {
                include 'head.analytics.php';
            };
        ?>

        <!-- Facebook -->
        <meta property="og:title" content="<?= 'UniStudyMate | ' .$title ?>">
        <meta property="og:type" content="website">
        <meta property="og:url" content="<?= $configurl ?>">
        <meta property="og:description" content="<?= $description ?>">
        <meta property="og:image" content="<?= $configurl ?>/dist/img/og.jpg">

        <!-- Twitter -->
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:title" content="<?= 'UniStudyMate | ' .$title ?>" />
        <meta name="twitter:description" content="<?= $description ?>" />
        <meta name="twitter:image:src" content="<?= $configurl ?>/dist/img/favicon/android-chrome-256x256.jpg" />

        <!-- Normalize CSS -->
        <link rel="stylesheet" href="https://necolas.github.io/normalize.css/7.0.0/normalize.css">
        <!-- Font Awesome -->
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <!-- Custom CSS -->
        <link rel="stylesheet" type="text/css" href="/dist/css/app.css?<?php echo time(); ?>">
        <link rel="stylesheet" type="text/css" href="/dist/css/responsive.css?<?php echo time(); ?>">
