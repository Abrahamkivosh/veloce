<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.ico">
    <title>Christa Networks registration</title>
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/1.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" type="text/css" href="library/js_date/datechooser.css">

	<!-- Font-->
	<link rel="stylesheet" type="text/css" href="css/montserrat-font.css">
	<link rel="stylesheet" type="text/css" href="fonts/material-design-iconic-font/css/material-design-iconic-font.min.css">
	<!-- Main Style Css -->
    <link rel="stylesheet" href="css/style.css"/>
<!--[if lte IE 6.5]>
<link rel="stylesheet" type="text/css" href="library/js_date/select-free.css"/>
<![endif]-->
</head>
<script src="library/js_date/date-functions.js" type="text/javascript"></script>
<script src="library/js_date/datechooser.js" type="text/javascript"></script>
<script src="library/javascript/pages_common.js" type="text/javascript"></script>
<script src="library/javascript/productive_funcs.js" type="text/javascript"></script>
<script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
<script type="text/javascript" src="library/javascript/ajax.js"></script>
<script type="text/javascript" src="library/javascript/ajaxGeneric.js"></script>
</head>

<body class="form-v10">
<?php
 include ("library/checklogin.php");
 $operator = $_SESSION['operator_user'];
	 
 include_once('library/config_read.php');
 $log = "visited page: ";
	require('db.php');
	include_once ("library/tabber/tab-layout.php");
	include ("menu-mng-users.php");

	include_once ("../services/smsService.php");

	$smsService = new smsService();

	
	
	
    // If form submitted, insert values into the database.
    if (isset($_REQUEST['username'])){
		$username = stripslashes($_REQUEST['username']); // removes backslashes
		$username = mysqli_real_escape_string($con,$username); //escapes special characters in a string
		$email = stripslashes($_REQUEST['email']);
		$email = mysqli_real_escape_string($con,$email);
		$password = stripslashes($_REQUEST['password']);
		$password = mysqli_real_escape_string($con,$password);
		$building = stripslashes($_REQUEST['building']);
		$building = mysqli_real_escape_string($con,$building);
		$country = stripslashes($_REQUEST['country']);
		$country = mysqli_real_escape_string($con,$country);
		$area = stripslashes($_REQUEST['area']);
		$area = mysqli_real_escape_string($con,$area);
		$location = stripslashes($_REQUEST['location']);
		$location = mysqli_real_escape_string($con,$location);
		$mobilephone = stripslashes($_REQUEST['mobilephone']);
		$mobilephone = mysqli_real_escape_string($con,$mobilephone);
		$firstname = stripslashes($_REQUEST['firstname']);
		$firstname = mysqli_real_escape_string($con,$firstname);
		$lastname = stripslashes($_REQUEST['lastname']);
		$lastname = mysqli_real_escape_string($con,$lastname);
		$planName = stripslashes($_REQUEST['planName']);
		$planName = mysqli_real_escape_string($con,$planName);
		$lastbill = date("Y-m-d H:i:s");
		$nextbill = $nextbill = date("Y-m-d H:i:s", strtotime("+30 days"));
		$enabled = 1;
		$ckey = md5(uniqid(rand(), true));
		$ctime = time();
		$todate = date("d M Y H:i:s",$ctime);
		$mng_hs_usr = md5(uniqid(rand(), true));
		$update = date("Y-m-d H:i:s");
		$trn_date = date("Y-m-d H:i:s");
		$updated_by = "Christa Networks Web Portal";
		$created_by = "Christa Networks Web Portal";
		$pppoepass = $username."christa";
		$init = 1000;
		
		
		$sql6 = "SELECT id, account FROM userinfo ORDER BY id DESC LIMIT 1";
		$resul6 = mysqli_query($con, $sql6);
		if(mysqli_num_rows($resul6)>0){
		
		while ($row = mysqli_fetch_array($resul6)){
											# code...
					$curracc = ($row['account']);
					$accounew = ($curracc + 1);
											
		}
		}else{
					$curracc = 1000;
					$accounew = ($curracc + 1);
		}
		
		$fullName = $firstname." ".$lastname;

		$firstchar = $mobilephone[0];
		if($firstchar=="0"){
			$newnum = substr($mobilephone, 1);
			 $fnumb = "+254".$newnum;
			 $fnum = "254".$newnum;
		}else{
				 $fnumb = substr($mobilephone, 1);
				 $fnum = $fnumb;
		}	
	
		$smsService->welcomeSMS($fnum, $fullName, $accounew, $planName, $username, $password);
		
		
		$query = "INSERT into `userinfo` (enableportallogin, firstname, lastname, username, portalloginpassword, email, mobilephone, creationdate, creationby, updatedate, updateby, ckey, ctime, mng_hs_usr, account) VALUES ('$enabled', '$firstname', '$lastname', '$username', '".md5($password)."', '$email', '$mobilephone', '$trn_date', '$created_by', '$update', '$updated_by', '$ckey', '$ctime', '$mng_hs_usr', '$accounew')";
		
		$query2 = "INSERT into `userbillinfo` (username, planName, email, phone, creationdate, creationby, updatedate, updateby, lastbill, nextbill, mng_hs_usr, account, area, location, building, country) VALUES ('$username', '$planName', '$email', '$mobilephone', '$trn_date', '$created_by', '$update', '$updated_by', '$lastbill', '$nextbill', '$mng_hs_usr', '$accounew', '$area', '$location', '$building', '$country')";
		
		$query3 = "INSERT into `radusergroup` (username, groupname, priority) VALUES ('$username', '$planName', '0')";
		
		$query4 = "INSERT into `radcheck` (username, attribute, op, value) 
					VALUES ('$username', 'Cleartext-Password', ':=', '$password')";
		$query5 = "INSERT into `radcheck` (username, attribute, op, value) 
					VALUES ('$username', 'Expiration', ':=', '$todate')";
		$query6 = "INSERT into `radcheck` (username, attribute, op, value) 
					VALUES ('$username', 'Simultaneous-Use', ':=', '1')";
		
		
								
		$check=mysqli_query($con,"select * from userinfo where username='$username'");
		$check2 = mysqli_query($con,"select * from radcheck where username='$username'");
		$check3=mysqli_query($con,"select * from userbillinfo where username='$username'");
		$check4 = mysqli_query($con,"select * from radusergroup where username='$username'");
		$checkrows2 = mysqli_num_rows($check2);
		$checkrows=mysqli_num_rows($check);
		$checkrows3=mysqli_num_rows($check3);
		$checkrows4=mysqli_num_rows($check4);
								
							if($checkrows>0){
								echo "</br></br>&nbsp&nbsp<h2>Duplicate information!</h2>";
							}else if($checkrows2>0){
								echo "</br></br>&nbsp&nbsp<h2>Duplicate information!</h2>";
							}else if($checkrows3>0){
								echo "</br></br>&nbsp&nbsp<h2>Duplicate information!</h2>";
							}else if($checkrows4>0){
								echo "</br></br>&nbsp&nbsp<h2>Duplicate information!</h2>";
							}else{
								$result = mysqli_query($con,$query);
								$result2 = mysqli_query($con,$query2);
								$result3 = mysqli_query($con,$query3);
								$result4 = mysqli_query($con,$query4);
								$result5 = mysqli_query($con,$query5);
								$result6 = mysqli_query($con,$query6);
								
							}
				
        if($result && $result2 && $result3 && $result4 && $result5 && $result6){
		
			
            echo "</br></br>&nbsp&nbsp<h2>Success!</h2>";
        }
    }else{
	
?>
    <div id="contentnorightbar">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div>
		<div class="form-v10-content">
                
				<form autocomplete="off" class="form-detail" action="#" method="post" id="myform">
				<div class="form-left">
					<h2>General Infomation</h2>
					<div class="form-row">
						<select name="planName" placeholder="pick your plan"">
						    <option class="option" value="title">Select your plan</option>
							<?php
$sqli = "SELECT * FROM billing_plans ORDER BY id ASC";
										$result2 = mysqli_query($con, $sqli);
											while ($row = mysqli_fetch_array($result2)) {
											# code...
											echo ('<option>'.$row['planName'].'</option>');
										}
										?>
						    
						</select>
						<span class="select-btn">
						  	<i class="zmdi zmdi-chevron-down"></i>
						</span>
					</div>
					<div class="form-group">
						<div class="form-row form-row-1">
							<input type="text" name="firstname" id="first_name" class="input-text" placeholder="First Name" required>
						</div>
						<div class="form-row form-row-2">
							<input type="text" name="lastname" id="last_name" class="input-text" placeholder="Last Name" required>
						</div>
					</div>
					
					<div class="form-row">
						<input type="text" name="building" class="company" id="company" placeholder="Building/APT" >
					</div>
					<div class="form-row">
						<input type="text" name="area" class="area" id="area" placeholder="Area" >
					</div>
					
					<div class="form-group">
						<div class="form-row form-row-3">
							<input type="text" name="username" class="business" id="business" placeholder="Username" required>
						</div>
						<div class="form-row form-row-4">
							<input type="password" name="password" class="password" id="password" placeholder="Password" required autocomplete="new-password">
							<span class="select-btn">
							  	<i class="iconify" onclick="myFunction()" data-icon="zmdi:eye" data-inline="false"></i>
							</span>
						</div>
						
					</div>
				</div>
				<div class="form-right">
					<h2>Contact Details</h2>
					<div class="form-row">
						<input type="text" name="location" class="street" id="demo" placeholder="Geolocation" required onfocus="getLocation()">
					</div>
						<div class="form-group">
						
						
					</div>
					<div class="form-row">
						<select name="country">
						    <option value="country">Country</option>
														<?php
$sqli = "SELECT * FROM country ORDER BY id ASC";
										$result2 = mysqli_query($con, $sqli);
											while ($row = mysqli_fetch_array($result2)) {
											# code...
											echo ('<option>'.$row['name'].'</option>');
										}
										?>
						    
						</select>
						<span class="select-btn">
						  	<i class="zmdi zmdi-chevron-down"></i>
						</span>
					</div>
					
					<div class="form-row">
						<input type="text" name="email" id="your_email" class="input-text" placeholder="Email">
					</div>
					<div class="form-row">
						<input type="text" required autocomplete="off" name="mobilephone" id="phone" class="input-text" required pattern="[0-9]+" placeholder="Phone">
						<span class="highlight"></span>
						<span class="bar"></span>
					</div>
					
					<div class="form-row-last">
						<input type="submit" name="submit" class="register" value="Register">
					</div>
				</div>
			</form>
                    
                    <!-- Form -->
                    
					
                
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper scss in scafholding.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper scss in scafholding.scss -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right Sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right Sidebar -->
        <!-- ============================================================== -->
    </div>
	<br /><br />


    <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
<script>
var x = document.getElementById("demo");

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) {
  x.innerHTML =position.coords.latitude +", "
   + position.coords.longitude;
}
</script>
	<script>
		function myFunction() {
		var x = document.getElementById("password");
		if (x.type === "password") {
		x.type = "text";
		} else {
		x.type = "password";
		}
		}
	</script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
    $('[data-toggle="tooltip"]').tooltip();
    $(".preloader").fadeOut();
    </script>
	<?php } ?>
<?php mysqli_close($con);?>
</body>

</html>