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
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
<?php
	require('db.php');
	require('basic.php');
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
		if(mysqli_num_rows($result) > 0){
		$resul6 = mysqli_query($con, $sql6);
		while ($row = mysqli_fetch_array($resul6)){
											# code...
					$curracc = ($row['account']);
					$accounew = ($curracc + 1);
											
		}
		}else{
					$curracc = 1000;
					$accounew = ($curracc + 1);
		}
		
		
		
		 $message = "Welcome {$firstname} {$lastname} Your account number is {$accounew}. Your plan is {$planName}. Please use Mpesa paybill No. {$paybill} to complete your payment." ;
		$firstchar = $mobilephone[0];
		if($firstchar=="0"){
			$newnum = substr($mobilephone, 1);
			 $fnum = "+254".$newnum;
		}else{
				 $fnum = $mobilephone;
		}	
		
		
		$query = "INSERT into `userinfo` (enableportallogin, firstname, lastname, username, portalloginpassword, email, mobilephone, creationdate, creationby, updatedate, updateby, ckey, ctime, mng_hs_usr, account) VALUES ('$enabled', '$firstname', '$lastname', '$username', '".md5($password)."', '$email', '$mobilephone', '$trn_date', '$created_by', '$update', '$updated_by', '$ckey', '$ctime', '$mng_hs_usr', '$accounew')";
		
		$query2 = "INSERT into `userbillinfo` (username, planName, email, phone, creationdate, creationby, updatedate, updateby, lastbill, nextbill, mng_hs_usr, account) VALUES ('$username', '$planName', '$email', '$mobilephone', '$trn_date', '$created_by', '$update', '$updated_by', '$lastbill', '$nextbill', '$mng_hs_usr', '$accounew')";
		
		$query3 = "INSERT into `radusergroup` (username, groupname, priority) VALUES ('$username', '$planName', '0')";
		
		$query4 = "INSERT into `radcheck` (username, attribute, op, value) 
					VALUES ('$username', 'Cleartext-Password', ':=', '$password')";
		$query5 = "INSERT into `radcheck` (username, attribute, op, value) 
					VALUES ('$username', 'Expiration', ':=', '$todate')";
		$query6 = "INSERT into `radcheck` (username, attribute, op, value) 
					VALUES ('$username', 'Simultaneous-Use', ':=', '1')";
		
		
								
		$check=mysqli_query($con,"select * from userinfo where username='$username' OR email='$email' OR mobilephone ='$mobilephone'");
		$check2 = mysqli_query($con,"select * from radcheck where username='$username'");
		$check3=mysqli_query($con,"select * from userbillinfo where username='$username' OR email='$email' OR phone ='$mobilephone'");
		$check4 = mysqli_query($con,"select * from radusergroup where username='$username'");
		$checkrows2 = mysqli_num_rows($check2);
		$checkrows=mysqli_num_rows($check);
		$checkrows3=mysqli_num_rows($check3);
		$checkrows4=mysqli_num_rows($check4);
								
							if($checkrows>0){
								header("Location: dup.php");
							}else if($checkrows2>0){
								header("Location: dup.php");
							}else if($checkrows3>0){
								header("Location: dup.php");
							}else if($checkrows4>0){
								header("Location: dup.php");
							}else{
								$result = mysqli_query($con,$query);
								$result2 = mysqli_query($con,$query2);
								$result3 = mysqli_query($con,$query3);
								$result4 = mysqli_query($con,$query4);
								$result5 = mysqli_query($con,$query5);
								$result6 = mysqli_query($con,$query6);
								
							}
				
        if($result && $result2 && $result3 && $result4 && $result5 && $result6){
			$smsService->welcomeSMS($fnum, $firstname, $accounew, $planName, $username, $password);
			
            header("Location: reg.php");
        }
    }else{
	
?>
    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center bg-dark">
            <div class="auth-box bg-dark border-top border-secondary">
                <div>
                    <div class="text-center p-t-20 p-b-20">
                        <span class="db"><img src="img/logo.png" alt="logo" /></span>
                    </div>
                    <!-- Form -->
                    <form class="form-horizontal m-t-20" action="" name="registration" action="" method="post>
                        <div class="row p-b-30">
                            <div class="col-12">
							<div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-success text-white" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>
                                    <select required style = "color: #0000B5; font-weight:400; width: 300px;" type="text" value="" name="planName" placeholder="pick your plan">
<option value="">Please select your plan</option>
<?php
$sqli = "SELECT * FROM billing_plans ORDER BY id ASC";
										$result2 = mysqli_query($con, $sqli);
											while ($row = mysqli_fetch_array($result2)) {
											# code...
											echo ('<option>'.$row['planName'].'</option>');
										}
										?>
</select>
                                </div>
								
								<div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-success text-white" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>
                                    <input type="text" name="firstname" class="form-control form-control-lg" placeholder="Firstname" aria-label="Username" aria-describedby="basic-addon1" required>
                                </div>
								
								<div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-success text-white" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>
                                    <input type="text" name="lastname" class="form-control form-control-lg" placeholder="Lastname" aria-label="Username" aria-describedby="basic-addon1" required>
                                </div>
								
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-success text-white" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>
                                    <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" required>
                                </div>
                                <!-- email -->
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-danger text-white" id="basic-addon1"><i class="ti-email"></i></span>
                                    </div>
                                    <input type="email" name="email"  class="form-control form-control-lg" placeholder="Email Address" aria-label="Username" aria-describedby="basic-addon1" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-warning text-white" id="basic-addon2"><i class="ti-pencil"></i></span>
                                    </div>
                                    <input type="number" maxlength="10" name="mobilephone" class="form-control form-control-lg" placeholder="Phone" aria-label="Password" aria-describedby="basic-addon1" required>
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-info text-white" id="basic-addon2"><i class="ti-pencil"></i></span>
                                    </div>
                                    <input type="password" name="password"class="form-control form-control-lg" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required>
                                </div>
                            </div>
                        </div>
                        <div class="row border-top border-secondary">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="p-t-20">
                                        <button class="btn btn-block btn-lg btn-info" type="submit">Sign Up</button>
                                    </div>
                                </div>
                            </div>
							
                        </div>
                    </form>
					<a href="http://www.lagaster.org/">&copy; <?php echo date("Y"); ?>&nbspLagaster Microsystems</a> <br /><br />
                </div>
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