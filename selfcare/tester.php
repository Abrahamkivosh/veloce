

<?php
$num = "+25472";
$firstchar = $num[0];
echo $firstchar."</br>";
if($firstchar=="0"){
	$newnum = substr($num, 1);
	echo $newnum."</br>";
	$fnum = "+254".$newnum;
	echo $fnum;
}else{
	echo $num."</br>";
	echo "we are working on {$num}";
}

$servername = 'localhost';
$username = 'root';
$password = 'Wadausita1';
$dbname = 'radius';

$con = mysqli_connect($servername, $username, $password, $dbname);

if (!$con) 
{
die("Connection failed: " . mysqli_connect_error());
}

$sq = "SELECT * FROM userbillinfo WHERE username = 'hannijkl'";
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
											echo $fnum;
											}
											else{
												$fnum = $pnumb;
											}
											
											}	
											
											$message = "Dear Customer, Your account {$firstchar} has been credited with Ksh.{$newnum}. Reference number is {$fnum}. Thank you for staying connected.";
											echo $message;
/* 
here you need to parse the json format 
and do your business logic e.g. 
you can use the Bill Reference number 
or mobile phone of a customer 
to search for a matching record on your database. 
*/ 

/* 
Reject an Mpesa transaction 
by replying with the below code 
*/ 
/* $transamount = 50;

$sqlis = "SELECT username FROM userinfo WHERE account = '1003'";
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
											
											
										}
$sqli2 = "SELECT * FROM billing_plans WHERE planName = '$plan'";
										$result4 = mysqli_query($con, $sqli2);
										while ($row = mysqli_fetch_array($result4)) {
											$plancost = ($row['planCost']);
										}									

if ($transamount==$plancost){
	
	


$sqli3 = "SELECT * FROM radcheck WHERE ((username = '$username')&&(attribute = 'Expiration'))";
										$result3 = mysqli_query($con, $sqli3);
											while ($row = mysqli_fetch_array($result3)) {
											# code...
											$rexp = ($row['value']);
											$eXpiry = strtotime($row['value']);
											$timenew = ($eXpiry + 2592000);
											$newdate = date("d M Y H:i:s", $timenew);
											
											
										}
$sqli4 = "UPDATE radcheck SET value='$newdate' WHERE ((attribute='Expiration')&&(username='$username'))";	
$result10 = mysqli_query($con, $sqli4);		}					 */		
echo "</br></br>ACCOUNT TESTING</br></br>";	

$sql6 = "SELECT id, account FROM userinfo ORDER BY id DESC LIMIT 1";
		$resul6 = mysqli_query($con, $sql6);
		while ($row = mysqli_fetch_array($resul6)){
											# code...
					$curracc = ($row['account']);
					$accounew = ($curracc + 1);
											
							}	
echo $curracc." ".$accounew."</br></br>";		

$sql7 = "SELECT * FROM userinfo";
										$results = mysqli_query($con, $sql7);
											while ($row = mysqli_fetch_array($results)) {
											# code...
											$phnum = ($row['mobilephone']);
										/* echo $phnum."</br></br>"; */
										}	
		


		
		?>
										
				
<form action="" method="POST"> 
<p><em>Send SMS</em></p>
<div class="container">
    <div>        
        <br style="clear:both">
            <div class="form-group col-md-4 ">                                
                <label id="phone" for="Contact">Contacts </label>
                <select class="form-control input-sm " type="textarea" id="phone" placeholder="+254" maxlength="140" rows="7" name="mcontact">
				<option>All</option>
				<?php
$sqli = "SELECT * FROM userinfo ORDER BY username ASC";
										$result2 = mysqli_query($con, $sqli);
											while ($row = mysqli_fetch_array($result2)) {
											# code...
											echo ('<option>'.$row['username'].'</option>');
										}
										?>
				</select>
                                        
            </div>
        


<div>      
	
        <br style="clear:both">
            <div class="form-group col-md-4 ">                                
                <label id="messageLabel" for="message">Message </label>
                <textarea class="form-control input-sm " type="textarea" name="msg" id="message" placeholder="Message" maxlength="140" rows="7"></textarea>
                    <span class="help-block"><p id="characterLeft" class="help-block " >You have reached the limit</p></span>                    
            </div>
        <br style="clear:both">
        <div class="form-group col-md-2">
        <button class="form-control input-sm btn btn-success disabled" id="btnSubmit" name="btnSubmit" type="submit" style="height:35px" > Send</button>    
    </div>
</div>
</form>
			

<?php
if(isset($_POST['btnSubmit']))
{

 

if($_POST['mcontact']=="All"){
	$sql7 = "SELECT * FROM userinfo";
										$results = mysqli_query($con, $sql7);
											while ($row = mysqli_fetch_array($results)) {
											# code...
											$phnum = ($row['mobilephone']);
										
	$firstchar = $phnum[0];
		if($firstchar=="0"){
			$newnum = substr($phnum, 1);
			 $fnum = "+254".$newnum;
		}else{
				 $fnum = $phnum;
		}									
										
										}	
}else{
	$usr=$_POST['mcontact'];
	$sql8 = "SELECT * FROM userinfo WHERE username = '$usr'";
										$results1 = mysqli_query($con, $sql8);
											while ($row = mysqli_fetch_array($results1)) {
											# code...
											$phnum = ($row['mobilephone']);
		$firstchar = $phnum[0];
		if($firstchar=="0"){
			$newnum = substr($phnum, 1);
			 $fnum = "+254".$newnum;
		}else{
				 $fnum = $phnum;
		}	
										}
}

$message= $_POST['msg'];
echo $message;
echo $fnum;
} 
$transamount=3800;
$plancost=2000;
$balance="";
if($transamount>=$plancost){
$newBalance = (($transamount-$plancost)+$balance);
}

print ($newBalance);
?>
