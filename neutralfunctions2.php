<?php
function title ($title) {
	print "<HTML>";
	print "<link href='styles/style.css' rel='stylesheet' type='text/css'>";
	print "<body>";
	print "<h2 style='font-size:750%'>" . $title . "</h2>";
	print "<br>";
	print "Seeding: ";
	$size	= size();
	print $size;
	print "GB | ";
	print "<a href='index.php'>Home</a> | ";
	print '<a href="remove.php">Eligible</a> | <a href="main.php">Update</a> |
	<a href="showdeleted.php">Show Deleted</a> | <a
	href="add.php">Add</a> | <a href="prune.php">Prune</a><br>';
	
}

function db () {
	$link					= mysql_connect('localhost','root','martin');
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db('torrentprune');
}

function checkboxUpdate($table,$column,$setColumnTo,$hash) {
			$checkboxQuery	=	sprintf("UPDATE $table
							SET	$column='$setColumnTo'
							WHERE hash='%s'",
							$hash);
			$result	= mysql_query($checkboxQuery);
			if (!$result) {
				$message	= 'Invalid query: ' . mysql_error() . "\n";
				$message	.= 'Whole query: ' . $checkboxQuery;
				die($message);
			}
	
		}
	 
function size() {
	$query	=	"SELECT day, SUM((down/1024)/1024) FROM deltas,torrents WHERE
	torrents.hash=deltas.hash AND dateofdeletion='0000-00-00' GROUP BY day
	ORDER BY day DESC";
	$result	=	mysql_query($query);
	$size	= mysql_fetch_row($result);
	$size	=	round($size[1],2);
	return $size;
}

function performance($n) {
	$query			= sprintf("SELECT * 
	FROM torrents, deltas 
	WHERE torrents.hash = '%s' 
	AND deltas.hash = '%s'
	ORDER BY day desc",
	$n,
	$n);
	$r	= mysql_query($query);
	$numRows = mysql_num_rows($r);
	$numRows = $numRows - 1;
	mysql_data_seek($r,0);
	$latest	= mysql_fetch_assoc($r);
	mysql_data_seek($r,$numRows);
	$earliest = mysql_fetch_assoc($r);
	$latestDay	= strtotime($latest['day']);
	$earliestDay	= strtotime($earliest['day']);
	$duration	= $latestDay - $earliestDay;
	$duration	=round((($duration / 60) / 60) /24,2); 
	if ($latest['down'] == 0) {
		$ratio = 0;
	} else {
		$ratio		= round($latest['up'] / $latest['down'],2);
	}
	if ($duration == 0) { $performance = 0;}
	else { $performance	= round($ratio / $duration,2);}
	$name	= $latest['name'];
	$hash	= $latest['hash'];
	$whitelist	=	$latest['whitelist'];
	$seeders	=	$latest['seeders'];
	$tracker	=	$latest['tracker'];
	$deletion	=	$latest['dateofdeletion'];
	$data	= array
	($performance,$duration,$ratio,$name,$hash,$whitelist,$seeders,$latest['day'],$tracker,$deletion);
	return $data;
}

function torrentlist() {
	$q				= "SELECT hash FROM torrents";
	$result	= mysql_query($q);
	return $result;
}

function makeTable($torrentInfo,$whitelist,$minSeeders,$minDuration) {
	$number = count($torrentInfo);
	$n = 0;
	print "<form method=post action=''>";
	print "<table>";
	print
	"<tr><td></td><td></td><td>Name</td><td>Duration</td><td>Ratio</td><td>Performance</td><td>Hash</td><td>Whitelist</td><td>Seeders</td><td>Tracker</td></tr>";
	for ($i = 0; $i < $number; $i++) {
		$a = $torrentInfo[$i];
		if ($a[5] == $whitelist || $a[6] <= $minSeeders || $a[1] < $minDuration
			|| $a[9] != '0000-00-00 00:00:00') { continue; }
		else {
			$n = $n + 1; 
			print
			"<tr><td><input type='checkbox' name='box[]'
			value='$a[4]'></td><td>$n</td><td>$a[3]</td><td>$a[1]</td><td/>$a[2]</td><td>$a[0]</td><td>$a[4]</td><td>$a[5]</td><td>$a[6]</td><td>$a[8]</td></tr>";
		}
	}
	echo"<tr><td colspan=10 align=center><input type=submit
	value=Select></form></td></tr>";
	echo "</table>\n";
}
function makeDeletedTable($torrentInfo) {
	$number = count($torrentInfo);
	$n = 0;
	print "<form method=post action=''>";
	print "<table>";
	print
	"<tr><td></td><td></td><td>Name</td><td>Duration</td><td>Ratio</td><td>Performance</td><td>Hash</td><td>Date
	Of Deletion</td><td>Seeders</td><td>Tracker</td></tr>";
	for ($i = 0; $i < $number; $i++) {
		$a = $torrentInfo[$i];
		$n = $n + 1; 
		print
		"<tr><td><input type='checkbox' name='box[]'
		value='$a[4]'></td><td>$n</td><td>$a[3]</td><td>$a[1]</td><td/>$a[2]</td><td>$a[0]</td><td>$a[4]</td><td>$a[9]</td><td>$a[6]</td><td>$a[8]</td></tr>";
		}
	echo"<tr><td colspan=10 align=center><input type=submit
	value=Select></form></td></tr>";
	echo "</table>\n";
}
