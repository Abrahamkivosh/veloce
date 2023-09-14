<?php
include "library/checklogin.php";
$operator = $_SESSION['operator_user'];

include_once 'library/config_read.php';
$log = "visited page: ";
include 'include/config/logging.php';
require 'db.php';
include_once "lang/main.php";

include_once "../services/smsService.php";

$smsService = new smsService();
$smsg = null;

include '../selfcare/sms.php';
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
	/* Increase the size of the Select2 container */
.select2-container {
  width: 300px; /* Adjust the width to your desired size */
}

/* Increase the size of the Select2 input field */
.select2-selection {
  height: 40px; /* Adjust the height to your desired size */
  font-size: 16px; /* Adjust the font size as needed */
}

</style>

</head>




<div id="wrapper">
<div id="innerwrapper">

<?php
$m_active = "Communication";
include_once "include/menu/menu-items.php";
include_once "include/menu/help-subnav.php";

?>

<?php
$successMessage = null;
$errorMessage = null;

if (isset($_POST['btnSubmit'])) {

    if ($_POST['mcontact'] == "All") {
        $sql7 = "SELECT * FROM userinfo";
        $results = mysqli_query($con, $sql7);
        while ($row = mysqli_fetch_array($results)) {
            # code...
            $phnum = ($row['mobilephone']);

            $firstchar = $phnum[0];
            if ($firstchar == "0") {
                $newnum = substr($phnum, 1);
                $fnum = "254" . $newnum;
                $fnumb = "+254" . $newnum;
            } else {
                $fnumb = substr($phnum, 1);
                $fnum = $fnumb;
            }
            $message = $_POST['msg'];
            $smsService->sendPlainText($fnum, $message);
			$successMessage = "Message sent successfully";
        }
    } else if ($_POST['mcontact'] == "ACTIVE") {

        $ctime = TIME();
        $sqli = "SELECT username, value, mobilephone FROM radcheck INNER JOIN userinfo USING (username) WHERE attribute = 'Expiration'";
        $result3 = mysqli_query($con, $sqli);
        while ($row = mysqli_fetch_array($result3)) {
            # code...

            $expiry = strtotime($row['value']);
            $uname = ($row['username']);
            $phnum = ($row['mobilephone']);

            if ($expiry > $ctime) {
                $firstchar = $phnum[0];
                if ($firstchar == "0") {
                    $newnum = substr($phnum, 1);
                    $fnum = "254" . $newnum;
                    $fnumb = "+254" . $newnum;
                } else {
                    $fnumb = substr($phnum, 1);
                    $fnum = $fnumb;
                }
                $message = $_POST['msg'];
				$smsService->sendPlainText($fnum, $message);
				#echo $uname."   ".$phone."  Active"."</br>";
				$successMessage = "Message sent successfully";
            }

        }

    } else if ($_POST['mcontact'] == "EXPIRED") {

        $ctime = TIME();
        $sqli = "SELECT username, value, mobilephone FROM radcheck INNER JOIN userinfo USING (username) WHERE attribute = 'Expiration'";
        $result3 = mysqli_query($con, $sqli);
        while ($row = mysqli_fetch_array($result3)) {
            # code...

            $expiry = strtotime($row['value']);
            $uname = ($row['username']);
            $phnum = ($row['mobilephone']);

            if ($expiry < $ctime) {
                $firstchar = $phnum[0];
                if ($firstchar == "0") {
                    $newnum = substr($phnum, 1);
                    $fnum = "254" . $newnum;
                    $fnumb = "+254" . $newnum;
                } else {
                    $fnumb = substr($phnum, 1);
                    $fnum = $fnumb;
                }
                $message = $_POST['msg'];
				$smsService->sendPlainText($fnum, $message);
				$successMessage = "Message sent successfully";
            }

        }

    } else {
        $usr = $_POST['mcontact'];
        $sql8 = "SELECT * FROM userinfo WHERE username = '$usr'";
        $results1 = mysqli_query($con, $sql8);
        while ($row = mysqli_fetch_array($results1)) {
            # code...
            $phnum = ($row['mobilephone']);
            $firstchar = $phnum[0];
            if ($firstchar == "0") {
                $newnum = substr($phnum, 1);
                $fnum = "254" . $newnum;
                $fnumb = "+254" . $newnum;
            } else {
                $fnumb = substr($phnum, 1);
                $fnum = $fnumb;
            }
        }
    }

    $message = $_POST['msg'];
    
	$smsService->sendPlainText($fnum, $message);
	$successMessage = "Message sent successfully";

}
?>

<form class="container" action="" method="POST">
	<div class="row">
		<div class="col-md-12">
			<h2 class="text text-center" >SEND MESSAGE</h2>
		</div>

	</div>


<div class="row">
    <div class="col-md-6 offset-3" >
        <br style="clear:both">
            <div class="form-group col-md-12 ">
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
    echo ('<option>' . $row['username'] . '</option>');
}
?>
				</select>

            </div>



<div>

        <br style="clear:both">
            <div class="form-group  ">
                <label id="messageLabel" for="message">Message </label>
                <textarea class="form-control input-sm " type="textarea" name="msg" id="message" placeholder="Message" maxlength="1000" rows="7"></textarea>
                    <span class="help-block"><p id="characterLeft" class="help-block " >You have reached the limit</p></span>
            </div>
        <br style="clear:both">
        <div class="form-group ">
        <button class=" btn btn-block btn-success disabled" id="btnSubmit" name="btnSubmit" type="submit" style="height:35px" > Send</button>
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

<script>
$(document).ready(function() {
	$('select').select2();
});
</script>
</body>
</html>
