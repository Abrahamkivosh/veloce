<?php
require('db.php');
require 'sms.php';
require('basic.php');
$username = "hannijkl";
$plan = "test";

$sqli2 = "SELECT * FROM billing_plans WHERE planName = '$plan'";
										$result4 = mysqli_query($con, $sqli2);
										while ($row = mysqli_fetch_array($result4)) {
											$plancost = ($row['planCost']);
											$timebank = ($row['planTimeBank']);
										}

$sqli3 = "SELECT * FROM radcheck WHERE ((username = '$username')&&(attribute = 'Expiration'))";
										$result3 = mysqli_query($con, $sqli3);
											while ($row = mysqli_fetch_array($result3)) {
											# code...
											$rexp = ($row['value']);
											$eXpiry = strtotime($row['value']);
											$timenow = time();
											
											if($timenow>$eXpiry){
											$timenew = (($eXpiry + ($timenow - $eXpiry)) + ($timebank));
											$newdate = date("d M Y H:i:s", $timenew);
											//echo $newdate."</br>";

											
											}else{
											$timenew = $eXpiry+$timebank;
											$newdate = date("d M Y H:i:s", $timenew);
											//echo $newdate."</br>";
											}
											
											
										}
$sq = "SELECT * FROM userbillinfo WHERE username = '$username'";
										$result = mysqli_query($con, $sq);
											while ($row = mysqli_fetch_array($result)) {
											# code...
											$plan = ($row['planName']);
											$account = ($row['account']);
											$phone = ($row['phone']);
											$firstchar = $phone[0];
													if($firstchar=="0"){
														$newnum = substr($phone, 1);
															$fnumber = "+254".$newnum;
echo $fnumber;
														}else{
														$fnumber = $phone;

														}
											
											
										}


?>