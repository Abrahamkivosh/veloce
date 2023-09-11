<?php

require('db.php');
require('basic.php');
$ctime = TIME();
$sqli = "SELECT username, value, mobilephone FROM radcheck INNER JOIN userinfo USING (username) WHERE attribute = 'Expiration'";
$result3 = mysqli_query($con, $sqli);
	while ($row = mysqli_fetch_array($result3)) {
											# code...
											
											 $expiry = strtotime($row['value']);
											 $uname = ($row['username']);
											 $phone = ($row['mobilephone']);
											 
											
											 
											 if ($expiry > $ctime){
												 echo $uname."   ".$phone."  Active"."</br>";
											 }
											 
											 if ($expiry < $ctime){
												 echo $uname."   ".$phone."  Expired"."</br>";
											 }
	}	
?> 