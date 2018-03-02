<?php

// ----- COMPOSE EMAIL ----- 
$headers = "From: $configTitle <contact@unistudymate.com>\r\n";
$headers .= "Reply-To: $from\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$message = wordwrap($message, 70, "\r\n");

mail($to, $subject, $message, $headers);

header ('Location: /includes/email.sent');		