<?php
include 'neutralfunctions.php';
include 'delete.php';
db();
$title	=	"Add";
title($title);
$dir1	=	"/mnt/alpha/toropt/userqueue/";
$dir2	=	"/mnt/alpha/toropt/torrents/";

$files = scandir($dir1);
$files2 = scandir($dir2);

if (count($files) > 2) { $file	=	$files[2]; $fullName = $dir1 . $file; } 
elseif (count($files2) > 2) {$file	=	$files2[2]; $fullName = $dir2 . $file;}
else { die('There are no files to add.'); }
$removeName	=	$fullName;
$fullName	= "\"" . $fullName . "\"";

addFromQueue($fullName);
unlink($removeName);

print "<tr><td>Added $fullName</td></tr>";
?>

