<?php
function removeAndDelete($hash) {
	$result = exec("transmission-remote -t $hash --remove-and-delete",$info,$ret);
	return $ret;
}
function addFromQueue($file) {
	$result	=	exec("transmission-remote -a $file",$info,$ret);
	return $ret;
}
?> 
