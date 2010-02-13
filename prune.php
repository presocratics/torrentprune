<?php
include 'neutralfunctions.php';
include 'delete.php';
db();
$title	=	"Deleted!";
title($title); // Common Heading
$time	= strftime('%Y-%m-%d %H:%M:%S');
$size	=	size();
$list	= torrentlist();
$output	=	array();
while ($names = mysql_fetch_array($list, MYSQL_ASSOC)) {
	foreach ($names as $l) {
		$out	= performance($l);
		array_push($output,$out);
	}
}
sort($output);
print "<table>";
for ($i = 0; $size > 200.00; $i++) {
	$a = $output[$i];
	if ($a[5] == 1 || $a[6] <= 1 || $a[1] < 27) { continue; }
	else {
		$hash	=	$a[4];
		removeAndDelete($hash);
		print "<tr><td>removed $a[3]</td>";
		$table	=	"torrents";
		$column	=	"dateofdeletion";
		$setColumnTo	=	$time;
		checkboxUpdate($table,$column,$setColumnTo,$hash);
		$size = size();
		print "<td>$size</td></tr>";
	}
}
print "</table>";
?>


