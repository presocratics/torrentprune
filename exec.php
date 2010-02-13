<HTML>
<link href="styles/style.css" rel="stylesheet" type="text/css">
<body>
<?php
$data	= array();
exec('deltas.pl',$data,$ret);
if ($ret != 0) {
	echo "Error in command";
} else {
	echo "<table>";
	$n = 0;
	foreach ($data as $line) {
		$n += 1;
		$info	= explode(",",$line);
		$hash	= $info[0];// $info[0] is the name
		$down	= $info[1];// $info[1] is the hash
		$up	= $info[2];// $info[2] is location
		$seeders	= $info[3]; // $info[5] is tracker
		$date	= $info[4];// $info[4] is date created
		$leechers		= $info[5];// $info[7] is format
		echo
		"<tr><td>$n</td><td>$date</td><td>$down</td><td>$up</td><td>$seeders</td></tr>";
	}
	echo "</table>";
}
?>
