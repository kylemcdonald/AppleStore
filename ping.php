<?php
$logFilename = "ping.txt";
$fh = fopen($logFilename, 'a');
$ip = $_SERVER['REMOTE_ADDR'];
fwrite($fh, time()."\t$ip\n");
fclose($fh);
?>