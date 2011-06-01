<?php
date_default_timezone_set('UTC');
$logFilename = "ping.txt";
$fh = fopen($logFilename, 'a');
$remoteIp = $_SERVER['REMOTE_ADDR'];
$internalIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
$remoteHostName = $_SERVER['REMOTE_HOST'];
$info = date(DATE_RFC822, time())."\t($remoteIp)\t($internalIp)\t($remoteHostName)\n";
fwrite($fh, $info);
fclose($fh);
echo $info;

echo '<table style="border-collapse: collapse;">';
foreach ($_SERVER as $key => $val)
    echo '<tr onMouseOver="this.style.backgroundColor=\'AAAABB\';" onMouseOut="this.style.backgroundColor=\'transparent\';"><td style="font-weight: bold; border-right: 2px solid #000000;">'.$key.'</td><td style="width: 100%;">'.(is_array($val)?nl2br(print_r($val,true)):$val).'</td></tr>';
echo '</table>';

?>