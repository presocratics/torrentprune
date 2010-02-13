<?php
include 'neutralfunctions.php';
db(); // Connect to database
$title	=	"Torrent Stats";
title($title); // Common Heading

$boxName	=	"box";
if (array_key_exists($boxName,$_POST)) { // Test if form was submitted
	$aWhitelist	= $_POST[$boxName];
	foreach($aWhitelist as $wl) {
		$table		=	"torrents"; // Set column and table to be updated
		$column		=	"whitelist";
		$setColumnTo=	"1";
		$hash		=	$wl;
		checkboxUpdate($table,$column,$setColumnTo,$hash); // UpdateDB
	}
} 

$list	= torrentlist(); // Get a list of Hashes for all torrents
$output = array();
while ($names = mysql_fetch_array($list, MYSQL_ASSOC)) {
	foreach ($names as $l) {
		$out	= performance($l);
		array_push($output,$out);
		}
}
rsort($output);
$update = $output[1][7]; // Get latest update information
print "Last Update: " . $update;
$whitelist	=	2;
$minSeeders	=	-2;
$minDuration	=	0;
makeTable($output,$whitelist,$minSeeders,$minDuration);
?>





