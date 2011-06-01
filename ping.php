<?php
date_default_timezone_set('UTC');
$logFilename = "ping.txt";
$fh = fopen($logFilename, 'a');
$remoteIp = $_SERVER['REMOTE_ADDR'];
$info = date(DATE_RFC822, time())."\t$remoteIp\n";
fwrite($fh, $info);
fclose($fh);
echo $info;
?>