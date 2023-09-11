

<?php
$servername = 'localhost';
$username = 'root';
$password = 'Wadausita1';
$dbname = 'radius';

$con = mysqli_connect($servername, $username, $password, $dbname);

if (!$con) 
{
die("Connection failed: " . mysqli_connect_error());
}

$username = "hannijkl";

										
$sq = "SELECT * FROM userbillinfo WHERE username = '$username'";
										$result = mysqli_query($con, $sq);
											while ($row = mysqli_fetch_array($result)) {
											# code...
											$plan = ($row['planName']);
											$account = ($row['account']);
											$pnumb = ($row['phone']);
											$firstchar = $pnumb[0];
											if($firstchar=="0"){
											$newnum = substr($pnumb, 1);
											$fnum = "+254".$newnum;
											
											}
											else{
												$fnum = $pnumb;
											}
											
											}	
											
																				
$sqli2 = "SELECT * FROM billing_plans WHERE planName = '$plan'";
										$result4 = mysqli_query($con, $sqli2);
										while ($row = mysqli_fetch_array($result4)) {
											$plancost = ($row['planCost']);
											$timebank = ($row['planTimeBank']);
										}
										
$sql6 = "SELECT id, account FROM userinfo ORDER BY id DESC LIMIT 1";
		$resul6 = mysqli_query($con, $sql6);
		while ($row = mysqli_fetch_array($resul6)){
											# code...
					$curracc = ($row['account']);
					$accounew = ($curracc + 1);
					echo $curracc."</br>";
					echo $accounew;
											
							}
										
$myfile = fopen("log.txt", "w") or die("Unable to open file!");
$txt = "John Doe\n";
fwrite($myfile, $txt);
$txt = "Jane Doe\n";
fwrite($myfile, $txt);
fclose($myfile);
										mysqli_close($con);
										?>
										
			