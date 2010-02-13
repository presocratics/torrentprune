<?php
function removeAndDelete($hash) {
	$result = exec("transmission-remote -t $hash --remove-and-delete",$info,$ret);
	return $ret;
}
?>

