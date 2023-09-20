<?php
	
	$username = "hannijkl";
function checkUserOnline(){
	include "dbb.php";
	
	$db = new db();
	
	$sql = $db->query('SELECT * FROM radacct WHERE username=?','$username')->numRows();
	
	if ($sql>=1) {
		$userStatus = "User is online";
	} else {
		$userStatus = "User is offline";
	}	

	echo $userStatus;
	return $userStatus;
}
 checkUserOnline();
 
 function getUsername(){
	
$sqlis = "SELECT username FROM userinfo WHERE account = '$billrefno'";
$resulte = mysqli_query($con, $sqlis);
	while ($row = mysqli_fetch_array($resulte)) {
	# code...
	$username = ($row['username']);
	
}
return $username;
}
getUsername();
?>