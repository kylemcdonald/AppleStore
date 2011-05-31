<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>Apple Store</title>
		<link rel="stylesheet" href="style.css" type="text/css"/>
	</head>
	<body>
		<p><span class="header"><a href="http://kylemcdonald.net/applestore/">Apple Store</a></span> is a <a href="http://fffff.at/speed-projects/">speed project</a> by <a href="http://kylemcdonald.net/">Kyle McDonald</a> that automatically posts pictures of people staring at computers.</p>

<?php
$dir = opendir("images");
while($cur = readdir($dir)) {
	if($cur[0] != "." && $cur != "") {
    $all[] = $cur;
	}
}
closedir($dir);

rsort($all);

$n = count($all);
$cur = intval($_GET["start"]);
$width = intval($_GET["width"]);
$perPage = intval($_GET["perpage"]);
$mode = $_GET["mode"];

if(!$perPage) {
	$perPage = 20;
}

if(!$mode) {
	$mode = "all";
}

if($mode == "all") {
	print("\t\t<p class=\"info\">$n people are staring at computers. <a href=\"?mode=each\">How many machines?</a></p>\n");

	// pagination could be cleaner, it's weird because i was doing it very wrong before
	for($i = 0; $i < $perPage;) {
			if($cur < $n) {
					$file = "images/$all[$cur]";
					print("\t\t<a href=\"$file\"><img src=\"$file\"");
					if($width != 0) {
							print(" width=\"$width\"");
					}
					print("/></a>\n");
					$i++;
					$cur++;
			} else {
					break;
			}
	}

	if($cur < $n) {
			$nextPage = 1 + intval(ceil($cur / $perPage));
			$total = intval(ceil($n / $perPage));
			print("\t\t<a href=\"?start=$cur\"><p>Click here for page $nextPage of $total</p></a>");
	}
} else if($mode == "each") {
	$allCount = count($all);
	for($i = 0; $i < $allCount; $i++) {
		$cur = $all[$i];
		$parts = explode(":", $cur);
		$ips[] = (string) substr($parts[1], 0, -4);
	}

	$ipCounts = array_count_values($ips);
	$uniqueCount = count($ipCounts);

	print("\t\t<p class=\"info\">$uniqueCount computers are staring at people. <a href=\"?mode=all\">How many people?</a></p>\n");

	while ($curValue = current($ipCounts)) {
		$curIp = key($ipCounts);
		print("<!-- $curIp ($curValue total) -->\n");
		next($ipCounts);

		// depending on how long people stay interested, this might need pagination too
		for($i = 0; $i < $allCount; $i++) {
			$curImg = $all[$i];
			if(strstr($curImg, $curIp)) {
					$file = "images/$curImg";
					print("\t\t<a href=\"$file\"><img src=\"$file\"");
					if($width != 0) {
							print(" width=\"$width\"");
					}
					print("/></a>\n");
				break;
			}
		}
	}
}

?>

	</body>
</html>
