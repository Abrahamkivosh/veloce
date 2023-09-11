<?php

    
        
	include_once('library/config_read.php');
    $log = "visited page: ";
    include('include/config/logging.php');
	require('db.php');
	include('../selfcare/sms.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title>Communication</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/1.css" type="text/css" media="screen,projection" />
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head>
 

<?php
    include_once ("lang/main.php");
?>

<div id="wrapper">
<div id="innerwrapper">

<?php
    $m_active = "Communication";
    include_once ("include/menu/menu-items.php");
	include_once ("include/menu/help-subnav.php");
	
?>
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
			 $fnum = "254".$newnum;
			 $fnumb = "+254".$newnum;
		}else{
				 $fnumb = substr($phnum, 1);
				 $fnum = $fnumb;
		}									
		$message= $_POST['msg'];								
		sendSms();	
		}	
}else if($_POST['mcontact']=="ACTIVE"){
	
$ctime = TIME();
$sqli = "SELECT username, value, mobilephone FROM radcheck INNER JOIN userinfo USING (username) WHERE attribute = 'Expiration'";
$result3 = mysqli_query($con, $sqli);
	while ($row = mysqli_fetch_array($result3)) {
											# code...
											
											 $expiry = strtotime($row['value']);
											 $uname = ($row['username']);
											 $phnum = ($row['mobilephone']);
											 
											
											 
											 if ($expiry > $ctime){
												 $firstchar = $phnum[0];
													if($firstchar=="0"){
														$newnum = substr($phnum, 1);
														$fnum = "254".$newnum;
			                                            $fnumb = "+254".$newnum;
												}else{
													$fnumb = substr($phnum, 1);
													$fnum = $fnumb;
												}									
													$message= $_POST['msg'];								
													sendSms();
												 #echo $uname."   ".$phone."  Active"."</br>";
											 }
											 											 
	}
	
	
}else if($_POST['mcontact']=="EXPIRED"){
	
$ctime = TIME();
$sqli = "SELECT username, value, mobilephone FROM radcheck INNER JOIN userinfo USING (username) WHERE attribute = 'Expiration'";
$result3 = mysqli_query($con, $sqli);
	while ($row = mysqli_fetch_array($result3)) {
											# code...
											
											 $expiry = strtotime($row['value']);
											 $uname = ($row['username']);
											 $phnum = ($row['mobilephone']);
											 
											
											 
											 if ($expiry < $ctime){
												 $firstchar = $phnum[0];
													if($firstchar=="0"){
														$newnum = substr($phnum, 1);
														$fnum = "254".$newnum;
			                                            $fnumb = "+254".$newnum;
												}else{
													$fnumb = substr($phnum, 1);
													$fnum = $fnumb;
												}									
													$message= $_POST['msg'];								
													sendSms();
												 #echo $uname."   ".$phone."  Active"."</br>";
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
			 $fnum = "254".$newnum;
			 $fnumb = "+254".$newnum;
		}else{
				 $fnumb = substr($phnum, 1);
				 $fnum = $fnumb;
		}
										}
}

$message= $_POST['msg'];
sendSms();
}
?>

<form action="" method="POST"> 
<p><em>Send SMS</em></p>
<div class="container">
    <div>        
        <br style="clear:both">
            <div class="form-group col-md-4 ">                                
                <label id="phone" for="Contact">Contacts </label>
                <select class="form-control input-sm " type="textarea" id="phone" placeholder="+254" maxlength="1000" rows="7" name="mcontact">
				<option>All</option>
				<option>ACTIVE</option>
				<option>EXPIRED</option>
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
                <textarea class="form-control input-sm " type="textarea" name="msg" id="message" placeholder="Message" maxlength="1000" rows="7"></textarea>
                    <span class="help-block"><p id="characterLeft" class="help-block " >You have reached the limit</p></span>                    
            </div>
        <br style="clear:both">
        <div class="form-group col-md-2">
        <button class="form-control input-sm btn btn-success disabled" id="btnSubmit" name="btnSubmit" type="submit" style="height:35px" > Send</button>    
    </div>
</div>
</form>


<script>
$(document).ready(function(){ 
    $('#characterLeft').text('1000 characters left');
    $('#message').keyup(function () {
        var max = 1000;
        var len = $(this).val().length;
        if (len >= max) {
            $('#characterLeft').text('You have reached the limit');
            $('#characterLeft').addClass('red');
            $('#btnSubmit').addClass('disabled');            
        } 
        else {
            var ch = max - len;
            $('#characterLeft').text(ch + ' characters left');
            $('#btnSubmit').removeClass('disabled');
            $('#characterLeft').removeClass('red');            
        }
    });    
});		
</script>		
		
		<div id="footer">
		
								<?php
        include 'page-footer.php';
?>
		
		</div>
		
</div>
</div>


</body>
</html>
