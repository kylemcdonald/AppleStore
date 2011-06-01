<?php
date_default_timezone_set('UTC');
$logFilename = "ping.txt";
$fh = fopen($logFilename, 'a');
$remoteIp = $_SERVER['REMOTE_ADDR'];
$remotePort = $_SERVER['REMOTE_PORT'];
$info = date(DATE_RFC822, time())."\t$remoteIp\t$remotePort\n";
fwrite($fh, $info);
fclose($fh);
echo $info;
?>