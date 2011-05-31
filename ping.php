<?php
$logFilename = "ping.txt";
$fh = fopen($logFilename, 'a');
fwrite($fh, time()."\t$ip\n");
fclose($fh);
?>