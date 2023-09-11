<?php

include 'sms.php';

$servername = 'localhost';
$username = 'root';
$password = '@christanetworks7879';
$dbname = 'radius';

$con = mysqli_connect($servername, $username, $password, $dbname);

if (!$con) 
{
die("Connection failed: " . mysqli_connect_error());
}
	$username = "hannijkl";
	$transamount = 100;
$sqlis = "SELECT username FROM userinfo WHERE account = '$billrefno'";
										$resulte = mysqli_query($con, $sqlis);
											while ($row = mysqli_fetch_array($resulte)) {
											# code...
											$username = ($row['username']);
											
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
															$fnum = "+254".$newnum;
														}else{
														$fnum = $phone;
														}
											
											
										}
$sqli2 = "SELECT * FROM billing_plans WHERE planName = '$plan'";
										$result4 = mysqli_query($con, $sqli2);
										while ($row = mysqli_fetch_array($result4)) {
											$plancost = ($row['planCost']);
											$timebank = ($row['planTimeBank']);
										}
$sqli = "SELECT * FROM userbillinfo WHERE username = '$username'";
										$result = mysqli_query($con, $sqli);
											while ($row = mysqli_fetch_array($result)) {
											# code...
											if (($row['balance'])>0)
											{
											$plan = ($row['planName']);
											$account = ($row['account']);
											$balance = ($row['balance']);
											}
											else{
												
												$account = ($row['account']);
												$balance = ($row['balance']);
												$balance=0;
											}
											
											
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
											echo $newdate."NEW DATE1</br>";

											
											}else{
											$timenew = $eXpiry+$timebank;
											$newdate = date("d M Y H:i:s", $timenew);
											echo $newdate."NEW DATE2</br>";
											}
											
											
										}
									

if ($transamount>$plancost){
	
$bal = ($transamount - $plancost);
	
$new_balance = ($bal + $balance);


$sqli4 = "UPDATE radcheck SET value='$newdate' WHERE ((attribute='Expiration')&&(username='$username'))";
$result10 = mysqli_query($con, $sqli4);
$sqli5 = "UPDATE userbillinfo SET balance='$new_balance' WHERE username='$username'";	
$result11 = mysqli_query($con, $sqli5);	

$message = "Your subscription has been renewed to {$newdate}. Your balance is Ksh.{$new_balance} ";	
sendSms();

}else if ($transamount<$plancost){
	
	$new_amount = ($transamount + $balance);
	
		if ($new_amount>$plancost){
			
			$new_balance = $new_amount-$plancost;
			
			
		$sqli7 = "UPDATE radcheck SET value='$newdate' WHERE ((attribute='Expiration')&&(username='$username'))";
		$result12 = mysqli_query($con, $sqli7);
		$sqli8 = "UPDATE userbillinfo SET balance='$new_balance' WHERE username='$username'";	
		$result13 = mysqli_query($con, $sqli8);
		$message = "Your subscription has been renewed to {$newdate}. Your balance is Ksh.{$new_balance} ";	
		sendSms();
		}else if ($new_amount<$plancost){
			$diff = $plancost-$new_amount;
			$sqli9 = "UPDATE userbillinfo SET balance='$new_amount' WHERE username='$username'";	
			$result14 = mysqli_query($con, $sqli9);
			$message = "Your subscription is less by {$diff}. Your balance is Ksh.{$new_amount} ";	
			sendSms();
		}else if ($new_amount == $plancost){
			$sqli10 = "UPDATE radcheck SET value='$newdate' WHERE ((attribute='Expiration')&&(username='$username'))";
			$result15 = mysqli_query($con, $sqli10);
			$new_balance = 0;
			$sqli8 = "UPDATE userbillinfo SET balance='$new_balance' WHERE username='$username'";	
			$result13 = mysqli_query($con, $sqli8);
			$message = "Your subscription has been renewed to {$newdate}. Your balance is Ksh.{$new_balance}";	
			sendSms();
		}else{
			echo "system error";
} 
	
	
}else if ($transamount == $plancost){
	$sqli11 = "UPDATE radcheck SET value='$newdate' WHERE ((attribute='Expiration')&&(username='$username'))";
			$result16 = mysqli_query($con, $sqli11);
	$message = "Your subscription has been renewed to {$newdate}. Your balance is Ksh.{$balance}";	
			sendSms();
}else{echo "Something went haywire";}
 

 
mysqli_close($con); 
?>