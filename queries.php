<HTML>
<link href="styles/style.css" rel="stylesheet" type="text/css">
<body>

<?php
echo '<link href="/srv/http/torrentprune/styles/style.css" rel="stylesheet" type="text/css">';
echo "<body>\n";
// Connect to the database
$link					= mysql_connect('localhost','root','martin');
if (!$link) {
		die('Could not connect: ' . mysql_error());
}
echo 'Connected successfully' . "\n";
mysql_select_db('torrentprune');
$q				= "SELECT hash FROM torrents";
// TODO make an array of hash values
$result	= mysql_query($q);

echo "<table>\n";
echo "<tr><td></td><td>Name</td><td>Duration</td><td>Ratio</td><td>Performance</td></tr>";
$output	=	array();
while ($hash = mysql_fetch_array($result, MYSQL_ASSOC)) {
		foreach ($hash as $h) { 
			$query			= sprintf("SELECT
			torrents.name,deltas.down,deltas.day,deltas.up 
			FROM torrents, deltas 
			WHERE torrents.hash = '%s' 
			AND deltas.hash = '%s'
			ORDER BY day desc",
			$h,
			$h);
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
			$duration	=(($duration / 60) / 60) /24;
			if ($latest['down'] == 0) {
				$ratio = 0;
			} else {
				$ratio		= $latest['up'] / $latest['down'];
			}
			$performance	= $ratio / $duration;
			$name	= $latest['name'];
			$data	= array ($performance,$duration,$ratio,$name);
			array_push($output,$data);
		}
}
rsort($output);
$n = 0;
foreach ($output as $out) {
	$n = $n + 1;
	print
	"<tr><td>$n</td><td>$out[3]</td><td>$out[1]</td><td/>$out[2]</td><td>$out[0]</td></tr>";
}
echo "</table>\n";
mysql_close($link);





