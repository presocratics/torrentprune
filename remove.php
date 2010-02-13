<?php
//include 'delete.php';
include 'neutralfunctions.php';
db();
$title	= "Eligible for Deletion";
title($title);

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
echo "<table>";
$output = array();
$list	= torrentlist();
$n = 0;
while ($names = mysql_fetch_array($list, MYSQL_ASSOC)) {
	foreach ($names as $l) {
		$out	= performance($l);
		array_push($output,$out);
		}
}
sort($output);
$whitelist	=	1;
$minSeeders	=	1;
$minDuration=	27;
makeTable($output,$whitelist,$minSeeders,$minDuration);
?>
