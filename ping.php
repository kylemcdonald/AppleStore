<?php
date_default_timezone_set('UTC');
$logFilename = "ping.txt";
$fh = fopen($logFilename, 'a');
$remoteIp = $_SERVER['REMOTE_ADDR'];
$headers = apache_request_headers(); 
$internalIp = $headers["X-Forwarded-For"];
$remoteHostName = $_SERVER['REMOTE_HOST'];
fwrite($fh, date(time())."\t$remoteIp\t$internalIp\t$remoteHostName\n");
fclose($fh);
?>