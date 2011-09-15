<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>People Staring at Computers</title>
		<link rel="stylesheet" href="style.css" type="text/css"/>
	</head>
	<body>
		<p><span class="header"><a href="http://kylemcdonald.net/psac/">People Staring at Computers</a></span> was a photographic intervention by <a href="http://kylemcdonald.net/">Kyle McDonald</a> that automatically posts pictures of people in computer stores around NYC. A custom app was used that took a picture each minute and uploaded it. Photos were then exhibited on siteg b, full screen, on every computer.</p>

<?php
$dir = opendir("images");
while($cur = readdir($dir)) {
	if($cur[0] != "." && $cur != "") {
    $all[] = $cur;
	}
}
closedir($dir);

$n = count($all);

if($n > 0) {
	rsort($all);
}

$cur = intval($_GET["start"]);
$width = intval($_GET["width"]);
$perPage = intval($_GET["perpage"]);

if(!$perPage) {
	$perPage = 120;
}

print("\t\t<p class=\"info\">$n people are staring at computers.</p>\n");

// pagination could be cleaner, it's weird because i was doing it very wrong before
for($i = 0; $i < $perPage;) {
		if($cur < $n) {
				$file = "images/$all[$cur]";
				print("\t\t<a href=\"$file\"><img src=\"$file\"");
				if($width != 0) {
						print(" width=\"$width\"");
				}
				$parts = explode(":", $cur);
				print("/>");
				print(date('F d Y', $parts[0]));
				print("</a>\n");
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

?>


<!-- Start of StatCounter Code -->
<script type="text/javascript">
sc_project=830491; 
sc_invisible=1; 
sc_partition=6; 
sc_security="436f22b9"; 
</script>
<script type="text/javascript"
src="http://www.statcounter.com/counter/counter.js"></script>
<!-- End of StatCounter Code -->

	</body>
</html>
