<?php
include 'neutralfunctions.php';
db();
$title	=	"Update";
title($title);
$time	= strftime('%Y-%m-%d %H:%M:%S');
$data					= array();
exec('/usr/local/bin/transmission-export.pl',$data,$ret);
if($ret != 0) {
	die ("Error transmission-export.pl in command");
}
foreach ($data as $line) {
	$info				= explode(",",$line);
	$name				= $info[0];// $info[0] is the name
	$hash				= $info[1];// $info[1] is the hash
	$path				= $info[2];// $info[2] is location
	$tracker			= $info[3]; // $info[5] is tracker
	$created			= $info[4];// $info[4] is date created
	$format				= $info[5];// $info[7] is format
	$bitrate			= $info[6];// $info[8] is bitrate

	mysql_select_db('torrentprune');

	$insertOrUpdate = sprintf("REPLACE INTO torrents (%s, %s, %s, %s, %s, %s, %s) 
	VALUES ('%s','%s','%s','%s','%s','%s','%s')",
	'hash',
	'name',
	'format',
	'bitrate',
	'created',
	'path',
	'tracker',
	mysql_real_escape_string($hash),
	mysql_real_escape_string($name),
	mysql_real_escape_string($format),
	mysql_real_escape_string($bitrate),
	mysql_real_escape_string($created),
	mysql_real_escape_string($path),
	mysql_real_escape_string($tracker)); 

	$result	= mysql_query($insertOrUpdate);
		if (!$result) {
				$message	= 'Invalid query: ' . mysql_error() . "\n";
				$message	.= 'Whole query: ' . $insertOrUpdate;
				die($message);
		}
		
}
$data					= array();
exec('/usr/local/bin/deltas.pl',$data,$ret);
if ($ret != 0) {
	die("Error in command");
}
foreach ($data as $line) { 
	$info				= explode(",",$line);
	$hash	= $info[0];// $info[0] is the name
	$down	= $info[1];// $info[1] is the hash
	$up	= $info[2];// $info[2] is location
	$seeders	= $info[3]; // $info[5] is tracker
	$day	= $time;// $info[4] is time of update
	$leechers		= $info[5];// $info[7] is format

	mysql_select_db('torrentprune');
	$deltaQuery = sprintf("INSERT INTO deltas (%s, %s, %s, %s, %s, %s) 
	VALUES ('%s','%s','%s','%s','%s','%s')",
	'hash',
	'day',
	'up',
	'down',
	'seeders',
	'leechers',
	mysql_real_escape_string($hash),
	mysql_real_escape_string($day),
	mysql_real_escape_string($up),
	mysql_real_escape_string($down),
	mysql_real_escape_string($seeders),
	mysql_real_escape_string($leechers)); 

	$result	= mysql_query($deltaQuery);
		if (!$result) {
				$message	= 'Invalid query: ' . mysql_error() . "\n";
				$message	.= 'Whole query: ' . $deltaQuery;
				die($message);
		}
}
echo "</table>";
?>
