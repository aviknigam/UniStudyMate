<?php 

require __DIR__ . '/../core/init.php';

session_destroy();

header("Location: ./login");