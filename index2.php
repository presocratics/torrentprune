<?php
$mysqli		=	new mysqli("localhost","root","martin","torrentprune");
if (mysqli_connect_errno()) {
	echo("Failed to connect, the error message is: " .
	mysqli_connect_error());
	exit();
}
print "<HTML><BODY><TABLE>";
$list		=	$mysqli->query(	"SELECT hash 
									FROM torrents");
while	($data	= $list->fetch_object())
{
	$performance	=	$mysqli->prepare("	SELECT name,hash	
											FROM torrents, deltas 
											WHERE torrents.hash = ? 
											AND deltas.hash = ? 
											ORDER BY day desc");
	$performance->bind_param("s",$hash);
	$hash	=	$data->hash;
	$performance->execute();
	$hash=null;
	$performance->bind_result($name,$hash);
	while ($r	=	$performance->fetch())
	{
		echo "<tr><td>" $name . $hash . "\n" . "</td></tr>";
	}
}

?>





