<?php
include_once('library/config_read.php');
$servername = 'localhost';
$username = 'root';
$password = 'Wadausita1';
$dbname = 'radius';

$con = mysqli_connect($servername, $username, $password, $dbname);

if (!$con) 
{
die("Connection failed: " . mysqli_connect_error());

}

$sql = "SELECT distinct(".$configValues['CONFIG_DB_TBL_RADCHECK'].".username),".$configValues['CONFIG_DB_TBL_RADCHECK'].".value,
		".$configValues['CONFIG_DB_TBL_RADCHECK'].".id,".$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".groupname as groupname, attribute, ".
		$configValues['CONFIG_DB_TBL_DALOUSERINFO'].".Account,".
		$configValues['CONFIG_DB_TBL_DALOUSERINFO'].".firstname, ".$configValues['CONFIG_DB_TBL_DALOUSERINFO'].".lastname
		, IFNULL(disabled.username,0) as disabled
		 FROM  
		".$configValues['CONFIG_DB_TBL_RADCHECK']." CROSS JOIN ".$configValues['CONFIG_DB_TBL_RADUSERGROUP']." ON 
		".$configValues['CONFIG_DB_TBL_RADCHECK'].".username=".$configValues['CONFIG_DB_TBL_RADUSERGROUP'].".username
 		CROSS JOIN ".$configValues['CONFIG_DB_TBL_DALOUSERINFO']."
		 ON ".$configValues['CONFIG_DB_TBL_RADCHECK'].".username=".$configValues['CONFIG_DB_TBL_DALOUSERINFO'].".username
		LEFT JOIN ".$configValues['CONFIG_DB_TBL_RADUSERGROUP']." disabled
		 ON disabled.username=".$configValues['CONFIG_DB_TBL_DALOUSERINFO'].".username AND disabled.groupname = 'daloRADIUS-Disabled-Users'";
	$res = mysqli_query($con, $sql);
	$row = mysqli_fetch_array($res);
											# code...
					echo"0->username"." ".($row[0])."</br>";
					echo"1->Password"." ".($row[1])."</br>";
					echo"2->id"." ".($row[2])."</br>";
					echo"3->groupname"." ".($row[3])."</br>";
					echo"4->attribute"." ".($row[4])."</br>";
					echo"5->Account"." ".($row[5])."</br>";
					echo"6->firstname"." ".($row[6])."</br>";
					echo"7->lastname"." ".($row[7])."</br>";
					
											
							
	
	$logDebugSQL = "";
	$logDebugSQL .= $sql . "\n";
	
	?>