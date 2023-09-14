<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

/*
Author: Abraham Kivosh
Website: https://www.lagaster.org/
Github: https://github.com/abrahamkivosh
*/
// include config  read file
require_once "../cpanel/library/config_read.php";


$dbHost = $configValues['CONFIG_DB_HOST'];
$dbUser = $configValues['CONFIG_DB_USER'];
$dbPass = $configValues['CONFIG_DB_PASS'];
$dbName = $configValues['CONFIG_DB_NAME'];
$dbPort = $configValues['CONFIG_DB_PORT'];

$con = mysqli_connect ($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
?>