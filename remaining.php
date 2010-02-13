<?php
include 'neutralfunctions.php';
db();
$data					= array();
exec('transmission-export.pl',$data,$ret);
if($ret != 0) {
	die ("Error in command");
}
foreach ($data as $line) {
	$info				= explode(",",$line);
	$hash	=	$info[1];
	$updateRemainingQuery	=	sprintf("UPDATE torrents 
										SET dateofdeletion='0000-00-00
										00:00:00'
										WHERE hash='%s'",
										$hash);

	$result	= mysql_query($updateRemainingQuery);
		if (!$result) {
				$message	= 'Invalid query: ' . mysql_error() . "\n";
				$message	.= 'Whole query: ' . $updateRemainingQuery;
				die($message);
		}
}
print "Success";
?>
