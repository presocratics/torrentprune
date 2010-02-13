<?php
include 'neutralfunctions.php';
db();
$title	=	"Deleted Torrents";
title($title);
echo "<table>";
$output = array();
$q				= "SELECT hash FROM torrents WHERE dateofdeletion !=
'0000-00-00'";
$list	= mysql_query($q);
$n = 0;
while ($names = mysql_fetch_array($list, MYSQL_ASSOC)) {
	foreach ($names as $l) {
		$out	= performance($l);
		array_push($output,$out);
		}
}
sort($output);
makeDeletedTable($output);
?>
